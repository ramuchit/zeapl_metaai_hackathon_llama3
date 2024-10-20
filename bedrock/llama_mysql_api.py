import json
from typing import Any, Dict, List
import os
from dotenv import load_dotenv
import boto3
from sqlalchemy import create_engine, text
import requests

load_dotenv()

os.environ['AWS_ACCESS_KEY_ID'] = os.getenv('AWS_KEY')
os.environ['AWS_SECRET_ACCESS_KEY'] = os.getenv('AWS_SECRET')

#Sql configuration
MYSQL_HOST = '127.0.0.1'
MYSQL_USERNAME = 'root'
MYSQL_PASSWORD = 'root'
MYSQL_DATABASE = 'hackathon'

# Initialize a Boto3 session and create a Bedrock runtime client
session = boto3.Session()
region = "us-west-2" # us-west-2 has better runtime quota
bedrock_client = session.client(service_name = 'bedrock-runtime', region_name = region)

# Define available models with their respective request limits
available_models = {
    "llama31-70b": "meta.llama3-1-70b-instruct-v1:0", # 400 requests per min
    "llama31-405b": "meta.llama3-1-405b-instruct-v1:0", # 50 requests per min
}
modelId = available_models["llama31-70b"]  # Select model for conversation


class FakeDatabase:
    """Sample fake database implementation."""
    def __init__(self):
        self.customers = self.get_result("SELECT * FROM customers")
        self.orders = self.get_result("SELECT * FROM orders")


    def get_result(self,query):
        print("Query is {query}")
        engine = create_engine(f"mysql+mysqlconnector://{MYSQL_USERNAME}:{MYSQL_PASSWORD}@{MYSQL_HOST}/{MYSQL_DATABASE}")
        connection = engine.connect() 
        resultset = connection.execute(text(query))
        r = resultset.mappings().all()
        # return json.dumps([dict(row) for row in r])
        return [dict(row) for row in r]
    
    def get_user(self, key:str, value:str) -> Dict[str, str]:
        """Return metadata of user."""
        if key in {"email", "phone", "username"}:
            # customer = get_result(f"select * from customers where phone='{value}' or email='{value}' or username='{value}';")
            # return customer if customer else f"Couldn't find a user with {key} of {value}"
            for customer in self.customers:
                if customer[key] == value:
                    return customer
            return f"Couldn't find a user with {key} of {value}"
        else:
            raise ValueError(f"Invalid key: {key}")

        return None

    def get_order_by_id(self, order_id: str) -> Dict[str, str]:
        """Return metadata of the order using order id."""
        # return get_result(f"select * from orders where id='{order_id}';")
        for order in self.orders:
            if order["id"] == order_id:
                return order
        return None

    def get_customer_orders(self, customer_id: str) -> List[Dict[str, str]]:
        """Return a list of orders for a specific customer."""
        return [order for order in self.orders if order["customer_id"] == customer_id]
        # return get_result(f"select * from orders where customer_id='{customer_id}';")

    def cancel_order(self, order_id: str) -> str:
        """Cancel an order if it's in 'Processing' status."""
        order = self.get_order_by_id(order_id)
        if order:
            if order["status"] == "Processing":
                order["status"] = "Cancelled"
                return "Cancelled the order"
            else:
                return "Order has already shipped.  Can't cancel it."
        return "Can't find that order!"
    
    def get_weather_callback(self, city: str):
        url = f"https://api.weatherapi.com/v1/current.json?key={os.getenv('WEATHER_API_TOKEN')}&q={city}"
        response = requests.request("GET", url)
        r = response.json()
        return r['current']['temp_c'],r['current']['condition']['text']
    

# Define all the tools avilable to the model
tool_config = {
    "tools": [
        {
            "toolSpec": {
                "name": "get_user",
                "description": "Looks up a user by email, phone, or username.",
                "inputSchema": {
                    "json": {
                        "type": "object",
                        "properties": {
                            "key": {
                                "type": "string",
                                "enum": ["email", "phone", "username"],
                                "description": "The attribute to search for a user by (email, phone, or username).",
                            },
                            "value": {
                                "type": "string",
                                "description": "The value to match for the specified attribute.",
                            },
                        },
                        "required": ["key", "value"],
                    }
                },
            }
        },
        {
            "toolSpec": {
                "name": "get_order_by_id",
                "description": "Retrieves the details of a specific order based on the order ID. Returns the order ID, product name, quantity, price, and order status.",
                "inputSchema": {
                    "json": {
                        "type": "object",
                        "properties": {
                            "order_id": {
                                "type": "string",
                                "description": "The unique identifier for the order.",
                            }
                        },
                        "required": ["order_id"],
                    }
                },
            }
        },
        {
            "toolSpec": {
                "name": "get_customer_orders",
                "description": "Retrieves the list of orders belonging to a user based on a user's customer id.",
                "inputSchema": {
                    "json": {
                        "type": "object",
                        "properties": {
                            "customer_id": {
                                "type": "string",
                                "description": "The customer_id belonging to the user",
                            }
                        },
                        "required": ["customer_id"],
                    }
                },
            }
        },
        {
            "toolSpec": {
                "name": "cancel_order",
                "description": "Cancels an order based on a provided order_id.  Only orders that are 'processing' can be cancelled",
                "inputSchema": {
                    "json": {
                        "type": "object",
                        "properties": {
                            "order_id": {
                                "type": "string",
                                "description": "The order_id pertaining to a particular order",
                            }
                        },
                        "required": ["order_id"],
                    }
                },
            }
        },
        {
            "toolSpec": {
                "name": "get_weather",
                "description": "Get the weather information on behalf any city",
                "inputSchema": {
                    "json": {
                        "type": "object",
                        "properties": {
                            "city": {
                                "type": "string",
                                "description": "With the help of city, able to fetch information of weather. Example - Delhi,Indore."
                            }
                        },
                        "required": [
                            "city"
                        ]
                    }
                }
            }
        }
    ],
    "toolChoice": {"auto": {}},
}

db = FakeDatabase()

def process_tool_call(tool_name: str, tool_input: Any) -> Any:
    print("process_tool_call called")
    """Process the tool call based on the tool name and input."""
    if tool_name == "get_user":
        return db.get_user(tool_input["key"], tool_input["value"])
    elif tool_name == "get_order_by_id":
        return db.get_order_by_id(tool_input["order_id"])
    elif tool_name == "get_customer_orders":
        return db.get_customer_orders(tool_input["customer_id"])
    elif tool_name == "cancel_order":
        return db.cancel_order(tool_input["order_id"])
    elif tool_name == "get_weather":
        return db.get_weather_callback(tool_input["city"])


def simple_chat():
    """Main chat function that interacts with the user and the LLM."""
    system_prompt = """
    You are a customer support chat bot for an online retailer called Zeapl.
    Your job is to help users look up their account, orders, and cancel orders.
    Be helpful and brief in your responses.
    You have access to a set of tools, but only use them when needed.
    If you do not have enough information to use a tool correctly, ask a user follow up questions to get the required inputs.
    Do not call any of the tools unless you have the required data from a user.
    """
    
    # Initial user message
    user_message = input("\nUser: ")
    messages = [{"role": "user", "content": [{"text": user_message}]}]

    while True:
        # If the last message is from the assistant, get another input from the user
        if messages[-1].get("role") == "assistant":
            user_message = input("\nUser: ")
            messages.append({"role": "user", "content": [{"text": user_message}]})

        # Parameters for API request to the Bedrock model
        converse_api_params = {
            "modelId": modelId,
            "system": [{"text": system_prompt}],
            "messages": messages,
            "inferenceConfig": {"temperature": 0.0, "maxTokens": 2048},
            "toolConfig": tool_config,  # Pass the tool config
        }

        # Get response from Bedrock model
        response = bedrock_client.converse(**converse_api_params)
        # Append assistant's message to the conversation
        messages.append(
            {"role": "assistant", "content": response["output"]["message"]["content"]}
        )
        print(json.dumps(response,indent=2))
        # If the model wants to use a tool, process the tool call
        if response["stopReason"] == "tool_use":
            tool_use = response["output"]["message"]["content"][
                -1
            ]  # Naive approach assumes only 1 tool is called at a time
            tool_id = tool_use["toolUse"]["toolUseId"]
            tool_name = tool_use["toolUse"]["name"]
            tool_input = tool_use["toolUse"]["input"]

            print(f"Claude wants to use the {tool_name} tool")
            print(f"Tool Input:")
            print(json.dumps(tool_input, indent=2))

            # Run the underlying tool functionality on the fake database
            tool_result = process_tool_call(tool_name, tool_input)

            print(f"\nTool Result:")
            print(json.dumps(tool_result, indent=2))

            # Append tool result message
            messages.append(
                {
                    "role": "user",
                    "content": [
                        {
                            "toolResult": {
                                "toolUseId": tool_id,
                                "content": [{"text": str(tool_result)}],
                            }
                        }
                    ],
                }
            )

        else:
            # If the model does not want to use a tool, just print the text response
            print(
                "\nZeapl Bot:"
                + f"{response['output']['message']['content'][0]['text']}"
            )


if __name__ == "__main__":
    simple_chat()