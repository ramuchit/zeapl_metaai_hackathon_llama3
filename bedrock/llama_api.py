import spacy
import boto3
import json
from dotenv import load_dotenv
import os
from sklearn.model_selection import train_test_split
from langdetect import detect
# from googletrans import Translator
from flask import Flask, jsonify, request
from llamaguard import LlamaGuard


load_dotenv()

os.environ['AWS_ACCESS_KEY_ID'] = os.getenv('AWS_KEY')
os.environ['AWS_SECRET_ACCESS_KEY'] = os.getenv('AWS_SECRET')

# Initialize LlamaGuard with the specific Llama model you're using
guard = LlamaGuard(model="meta.llama3-1-70b-instruct-v1:0'", moderation_level="strict")
# A dictionary to hold session data for each user
conversations = {}
app = Flask(__name__)
   

@app.route('/support',methods=['POST'])
def get_lama_response():
    if not request.json or 'prompt' not in request.json:
        return jsonify({'error': 'Invalid Payload'}), 400
    # Initialize a boto3 client for AWS Bedrock
    bedrock_client = boto3.client('bedrock-runtime', region_name='ap-south-1')
    formatted_prompt = f"""
                        <|begin_of_text|><|start_header_id|>user<|end_header_id|>
                        {request.json['prompt']}
                        <|eot_id|>
                        <|start_header_id|>assistant<|end_header_id|>
                        """
    native_request = {
        "prompt": formatted_prompt,
        "max_gen_len": 512,
        "temperature": 0.5,
    }
    # Define the model ARN (replace with your actual model ARN)
    model_arn = 'meta.llama3-8b-instruct-v1:0'
    # Invoke the Bedrock model using its ARN
    response = bedrock_client.invoke_model(
        modelId=model_arn,  # Model ARN
        contentType="application/json",  # Content type
        body=json.dumps(native_request) # Input data as JSON
    )
    return response['body'].read().decode(),200


@app.route('/generate',methods=['POST'])
def simple_chat():
    desired_tone = "friendly and informative"
    blacklist = ["gupshup", "clevertap"]

    system_prompt = f"""
    Task: Multi-Action Task Handling

    User Instructions:
    Please provide a response in a {desired_tone} manner and avoid using any of the following words or topics: {', '.join(blacklist)}. 
    Please assist with any of the following tasks:

    Book a flight.
    Find a restaurant.
    Play music.
    Get weather information.
    Book a taxi.
    Add a channel in DishTV

    Responses:

    Flight Booking: Ask for the departure city, destination, preferred date and time, number of passengers, and class of travel. If external tools are available, attempt to book the flight. Otherwise, provide recommendations for flight booking websites.

    Find a Restaurant: Request the user's location, type of cuisine, and any specific preferences like budget or ambiance. Use restaurant recommendation services to suggest restaurants. If a reservation is needed, ask the user for further details like time and date.

    Play Music: Inquire about the song or artist the user would like to listen to. If available, use an API to stream the song, or suggest a music platform where the user can play the requested track.

    Get Weather Information: Ask for the user's location or desired city and provide the current weather. You may integrate an external weather API like OpenWeather to fetch live data.

    Book a Taxi: Ask for the pickup location, destination, and preferred time. If connected to a taxi service API, initiate a booking. If not, suggest available ride-hailing services in the area.

    Add a channel: Ask channel name and operator and suggest trending serials and drama.

    System Guidelines:

    For every task, ensure that you request all necessary information from the user.
    Respond with clear and actionable steps, and where applicable, confirm actions before proceeding (e.g., booking a flight or taxi).
    Always offer alternative suggestions when a direct action (like booking or playing music) cannot be performed within the system.

    """
    if not request.json or 'text' not in request.json:
        return jsonify({'error': 'Invalid data'}), 400
    # Initial user message
    user_message =  guard.generate(request.json['text'])
    session_id = request.json.get("session_id")
    if session_id not in conversations:
        conversations[session_id] = []

    messages = conversations[session_id]

    # Append the user's message to the conversation history
    messages.append({"role": "user", "content": [{"text":user_message}]})

    region = "us-west-2" # us-west-2 has better runtime quota
    # Initialize a Boto3 session and create a Bedrock runtime client
    session = boto3.Session()
    bedrock_client = session.client(service_name = 'bedrock-runtime', region_name = region)

    # Parameters for API request to the Bedrock model
    # modelId='meta.llama3-70b-instruct-v1:0'
    modelId='meta.llama3-1-70b-instruct-v1:0'
    converse_api_params = {
        "modelId": modelId,
        "system": [{"text": system_prompt}],
        "messages": messages,
        "inferenceConfig": {"maxTokens": 2048}
    }
    # Get response from Bedrock model
    response = bedrock_client.converse(**converse_api_params)

    # Append assistant's message to the conversation
    messages.append(
        {"role": "assistant", "content": response["output"]["message"]["content"]}
    )
    if user_message.lower() == 'bye':
        conversations[session_id] = []
    else:
        conversations[session_id] = messages
    return jsonify({'generatation':response["output"]["message"]["content"][0]['text'],'result':response}), 200

# # Run the app
if __name__ == '__main__':
    app.run(debug=True)