<?php
  define('ENCODE','ENCODE');
  define('DECODE','DECODE');

  // Status
  define('typeStatusRead','read');
  define('typeStatusDelivered','delivered');
  define('typeStatusFailed','failed');
  define('typeStatusAck','ack');
  define('typeStatusSent','sent');


  //Type of whatsapp message
  define('typeText','text');
  define('typePhoto','photo');
  define('typeImage','image');
  define('typeVideo','video');
  define('typeDocument','document');
  define('typeAudio','audio');
  define('typeVoice','voice');
  define('typeLocation','location');
  define('typeData','data');
  define('typeButton','button');

  // Send Methods
  define('typeSendMessage','sendMessage');
  define('typeSendPhoto','sendPhoto');
  define('typeSendAudio','sendAudio');
  define('typeSendDocument','sendDocument');
  define('typeSendVideo','sendVideo');
  define('typeSendVoice','sendVoice');
  define('typeSendLocation','sendLocation');

  define('typeSendResponse','send response');

  define('typeInteractiveButton','IB');
  define('typeInteractiveList','IL');
  define('typeInteractiveListTemp','IL_TEMP');
  define('typeInteractive','interactive');
  define('typeCarousel','CAROUSEL');
  define('typeTemplate','TEMPLATE');
  // flow
  define('typeFlow','flow');
  define('typeTemFlow','tflow');
  define('typeFlowRes','nfm_reply');

  // Catalog Types 
  define('typeSingleProduct','SP');
  define('typeMultipleProduct','MP');
  define('typeOrder','order');

  //Validator types
  define('secondaryValidator', 'S');
  define('messageTypeValidator', 'P');
  define('regexValidator', 'R');
  define('customValidator', 'C');
  define('apiValidator', 'A');
  define('directValidator', 'D');

  //DEFINITION OF UNIVERSAL API's
  define('universalUserAccess', 'UA');

  define('HSMINAVLIDUSERRORLIST', array("WA019", "WA008", "1013", "1006"));

 ?>
