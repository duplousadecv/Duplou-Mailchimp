<?php
    // Dicta el tipo de contenido que va a devolver la aplicación
    header('Content-Type: application/json');

    // Checa si el usuario envió el correo electrónico en la solicitud
    if(!isset($_POST['email']) || empty($_POST['email'])){
        $response = array('exito' => false, 'message' => 'Por favor introduce un email válido');
        echo json_encode($response);
    }else{
        //Mailchimp 

        // Arma los datos que se requieren enviar para dar de alta a una persona en la lista
        $data = array(
            'email_address' => $_POST['email'],
            'status' => 'subscribed'
        );

        // Manda llamar la función que agrega un miembro a la lista
        if(agregarMiembroMC($data) == 200){
            $response = array('exito' => true, 'message' => 'Tu suscripción se realizó con éxito');
        }else{
            $response = array('exito' => false, 'message' => 'Existió un error al realizar la solicitud');
        }
        echo json_encode($response);  
    }

    // Funcion para dar de alta a un nuevo miembro
    function agregarMiembroMC($data) {
    	
        $apiKey = ''; // Llave de APi de Mailchimp, ejemplo: xxxxxxxxxxxxxxxxxxxx-us7
        $servidorMC = ''; // Sólo colocar la última parte de la llave, ejemplo: us7
        $lista = '' // Colocar el número de la lista a la que quieres suscribir al usuario

    	$ch = curl_init('https://'. $servidorMC .'.api.mailchimp.com/3.0/lists/'. $lista .'/members/'.md5(strtolower($data['email_address'])));
    	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
    	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    	$result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $httpCode;
        
    }
?> 	   