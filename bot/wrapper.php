<?php
    date_default_timezone_set('Asia/Calcutta');
    class wrapper{
        //data here is an array
        function wrap($data, $type){
             if($type==DECODE){
                $response['caller'] = $data['caller'];
                $response['caller']['decode'] = 'false';
                if (array_key_exists('messages', $data)) {
                    if (array_key_exists('type', $data['messages'][0])) {
                        $response['data']['type'] = $data['messages'][0]['type'];
                        switch($response['data']['type']){
                            case typeText:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              $response['data']['body'] = $data['messages'][0]['text']['body'];
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeImage:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              //SET body as unsupported
                              $response['data']['body'] = "UNSUPPORTED-TYPE";
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['typeid'] = $data['messages'][0]['image']['id'];
                              $response['data']['mime_type'] = $data['messages'][0]['image']['mime_type'];
                              $response['data']['sha256'] = $data['messages'][0]['image']['sha256'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeVideo:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              //SET body as unsupported
                              $response['data']['body'] = "UNSUPPORTED-TYPE";
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['typeid'] = $data['messages'][0]['video']['id'];
                              $response['data']['mime_type'] = $data['messages'][0]['video']['mime_type'];
                              $response['data']['sha256'] = $data['messages'][0]['video']['sha256'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeAudio:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              //SET body as unsupported
                              $response['data']['body'] = "UNSUPPORTED-TYPE";
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['typeid'] = $data['messages'][0]['audio']['id'];
                              $response['data']['mime_type'] = $data['messages'][0]['audio']['mime_type'];
                              $response['data']['sha256'] = $data['messages'][0]['audio']['sha256'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeVoice:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              //SET body as unsupported
                              $response['data']['body'] = "UNSUPPORTED-TYPE";
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['typeid'] = $data['messages'][0]['voice']['id'];
                              $response['data']['mime_type'] = $data['messages'][0]['voice']['mime_type'];
                              $response['data']['sha256'] = $data['messages'][0]['voice']['sha256'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeDocument:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              //SET body as unsupported
                              $response['data']['body'] = "UNSUPPORTED-TYPE";
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['typeid'] = $data['messages'][0]['document']['id'];
                              $response['data']['mime_type'] = $data['messages'][0]['document']['mime_type'];
                              $response['data']['sha256'] = $data['messages'][0]['document']['sha256'];
                             /* $response['data']['caption'] = $data['messages'][0]['document']['caption'];
                              $response['data']['filename'] = $data['messages'][0]['document']['filename'];*/
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeLocation:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              $response['data']['latitude'] = $data['messages'][0]['location']['latitude'];
                              $response['data']['longitude'] = $data['messages'][0]['location']['longitude'];
                              $response['data']['body'] = $data['messages'][0]['location']['latitude'].",".$data['messages'][0]['location']['longitude'];
                              if(array_key_exists("name", $data['messages'][0]['location']))
                                $response['data']['name'] = $data['messages'][0]['location']['name'];
                              if(array_key_exists("address", $data['messages'][0]['location']))
                                $response['data']['address'] = $data['messages'][0]['location']['address'];
                              if(array_key_exists("url", $data['messages'][0]['location']))
                                $response['data']['url'] = $data['messages'][0]['location']['url'];
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeSendResponse:
                              if(array_key_exists('messages', $data['response_temp'])){
                                  $response['response']['messageid'] = $data['response_temp']['messages'][0]['id'];
                                  $response['response']['code'] = "200";
                                  $response['response']['status'] = "SUCCESS";
                              } else if(array_key_exists('contacts', $data['response_temp']) AND $data['response_temp']['contacts'][0]['status'] == 'valid'){
                                  $response['response']['code'] = "200";
                                  $response['response']['status'] = "SUCCESS";
                              } else {
                                  if(array_key_exists('errors', $data['response_temp'])) {
                                    $response['response']['code'] = $data['response_temp']['errors'][0]['code'];
                                    $response['response']['status'] = $data['response_temp']['errors'][0]['title']." - ".$data['response_temp']['errors'][0]['details'];
                                  } else {
                                    $response['response']['code'] = $data['response_temp']['code'];
                                    $response['response']['status'] = $data['response_temp']['message'];
                                  }
                              }
                              $response['caller']['decode'] = 'true';
                              unset($response['response_temp']);
                              break;
                            case typeButton:
                              $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                              $response['data']['from'] = $data['messages'][0]['from'];
                              $response['data']['msgID'] = $data['messages'][0]['id'];
                              $response['data']['body'] = $data['messages'][0]['button']['payload'];
                              $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                              $response['data']['replyMsgID'] = $data['messages'][0]['context']['id'];
                              $response['data']['payloadText'] = $data['messages'][0]['button']['text'];
                              $response['caller']['decode'] = 'true';
                              break;
                            case typeInteractive:
                              if ($data['messages'][0]['interactive']['type'] == typeFlowRes) {
                                $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                                $response['data']['from'] = $data['messages'][0]['from'];
                                $response['data']['msgID'] = $data['messages'][0]['id'];
                                $response['data']['subType'] = $data['messages'][0]['interactive']['type'];
                                $response['data']['body'] = $data['messages'][0]['interactive']['nfm_reply']['body'];
                                $response['data']['nfm_reply_res'] = @json_decode($data['messages'][0]['interactive']['nfm_reply']['response_json'],true)?:"";
                                $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                                $response['caller']['decode'] = 'true';
                              }else{
                                $response['data']['name'] = $data['contacts'][0]['profile']['name'];
                                $response['data']['from'] = $data['messages'][0]['from'];
                                $response['data']['msgID'] = $data['messages'][0]['id'];
                                $response['data']['subType'] = $data['messages'][0]['interactive']['type'];
                                $response['data']['body'] = $data['messages'][0]['interactive'][$data['messages'][0]['interactive']['type']]['id'];
                                $response['data']['payloadTitle'] = @$data['messages'][0]['interactive'][$data['messages'][0]['interactive']['type']]['title']?:"";
                                $response['data']['payloadDescription'] = @$data['messages'][0]['interactive'][$data['messages'][0]['interactive']['type']]['description']?:"";
                                $response['data']['timestamp'] = $data['messages'][0]['timestamp'];
                                $response['caller']['decode'] = 'true';
                              }
                              break;
                        }
                    }
                } else if(array_key_exists('status', $data['statuses'][0])){
                    foreach ($data['statuses'] as $key => $value) {
                        $response['data'][$key]['message_id'] = $value['id'];
                        $response['data'][$key]['recipient_id'] = $value['recipient_id'];
                        $response['data'][$key]['timestamp'] = $value['timestamp'];
                        $response['caller'][$key]['decode'] = 'true';
                        switch ($value['status']) {
                            case typeStatusRead:
                                $response['data'][$key]['status'] = $value['status'];
                                break;
                            case typeStatusSent:
                                $response['data'][$key]['status'] = $value['status'];
                                break;
                            case typeStatusDelivered:
                                $response['data'][$key]['status'] = $value['status'];
                                break;
                            case typeStatusFailed:
                                $response['data'][$key]['message'] = @$value['errors'][0]['title']." - ".@$value['errors'][0]['error_data']['details'];
                                $response['data'][$key]['message_code'] = @$value['errors'][0]['code'];
                                $response['data'][$key]['status'] = $value['status'];
                                break;
                        }
                    }
                }
            } else if($type==ENCODE){
                if(array_key_exists('message', $data)) {
                    foreach($data['message'] as $key => $value) {
                        $message = array();
                        $response[$key]["messaging_product"] = "whatsapp";
                        $response[$key]["recipient_type"] = "individual";
                        $response[$key]["to"] = $data['data']['from'];
                        switch(strtolower($value['type'])){
                            case strtolower(typeText):
                                $response[$key]["type"] = $value['type'];
                                $response[$key]['text']['body'] = $value['message_content'];
                                break;
                            case strtolower(typeImage):
                                $response[$key]["type"] = $value['type'];
                                $response[$key][$value['type']]['link'] = $value['url'];
                                if(array_key_exists("caption",$value)) {
                                    $response[$key]['image']['caption'] = $value['caption'];
                                }
                                break;
                            case strtolower(typeVideo):
                                $response[$key]["type"] = $value['type'];
                                $response[$key][$value['type']]['link'] = $value['url'];
                                if(array_key_exists("caption",$value)) {
                                    $response[$key]['video']['caption'] = $value['caption'];
                                }
                              break;
                            case strtolower(typeAudio):
                                $response[$key]["type"] = $value['type'];
                                $response[$key][$value['type']]['link'] = $value['url'];
                              break;
                            case strtolower(typeDocument):
                                $response[$key]["type"] = $value['type'];
                                $response[$key][$value['type']]['link'] = $value['url'];
                                if(array_key_exists("caption",$value)) {
                                    $response[$key][$value['type']]['caption'] = $value['caption'];
                                }
                                if(array_key_exists("filename",$value)) {
                                    $response[$key][$value['type']]['filename'] = $value['filename'];
                                }
                              break;
                            case strtolower(typeLocation):
                                $template['latitude'] = $value['latitude'];
                                $template['longitude']= $value['longitude'];
                                if(array_key_exists("name",$value)) {
                                    $template['name'] = $value['name'];
                                }
                                if(array_key_exists("address",$value)) {
                                    $template['address'] = $value['address'];
                                }
                                $response[$key]['type'] = "location";
                                $response[$key]['location'] = $template;
                                $response[$key] = $response[$key];
                                break;
                            case strtolower(typeFlow):
                                $response[$key]["type"] = 'interactive';
                                $response[$key]['interactive']['type'] = 'flow';
                                $response[$key]['interactive']['body']['text'] = $value['message_content'];
                                $response[$key]['interactive']['action']['name'] = 'flow';
                                $response[$key]['interactive']['action']['parameters']['flow_message_version'] = "3";
                                $response[$key]['interactive']['action']['parameters']['flow_token'] = $value['flow_token'];;
                                $response[$key]['interactive']['action']['parameters']['flow_id'] = $value['flow_id'];
                                $response[$key]['interactive']['action']['parameters']['flow_cta'] = $value['button_cta'];;
                                $response[$key]['interactive']['action']['parameters']['flow_action'] = "navigate";
                                $response[$key]['interactive']['action']['parameters']['flow_action_payload']['screen'] = $value['screen'];
                                break;
                        }
                    }
                }
            }
            return $response;
        }
    }
?>
