<?php

require_once 'vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $name = $_POST['name']?? 'null';
    $surname = $_POST['surname']?? 'null';
    $numberPeople = $_POST['numberPeople']?? 'null';
    $phone = $_POST['phone']?? 'null';
    $dateStart = $_POST['dateStart']?? 'null';
    $dateEnd = $_POST['dateEnd']?? 'null';

    $arrayTranlstate = ['Imie', 'Nazwisko', 'Ilość Osób', 'Telefon', 'DataRozpoczcia','DataZakonczenia'];
    $message = null;
    $array = array_values($_POST);
    foreach ($array as $key => $item) {
        $message .= $arrayTranlstate[$key] . ' - ' . $item . "<br>";

    }

    if ($dateStart > $dateEnd)
        echo json_encode(['messageType' => 'danger']);
    else {

        try{
            $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('****') //twoj email do konta gmail
                ->setPassword('****'); //haslo do konta gmail
            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance('Tytuł')
                ->setFrom('pablos.lukowski@wp.pl')
                ->setTo(array('pablos.lukowski@wp.pl')) //do kogo beda szly maile.. po przecinku mozna podać wiele emaili
                ->setBody($message, 'text/html');

            if ($mailer->send($message)) {
                header('Content-type:application/json;charset=utf-8');
                echo json_encode(['messageType' => 'success','message'=>'wysłano']);
            }
        }catch(\Swift_TransportException $e){
            echo json_encode(['messageType' => 'danger','message'=>'błąd']);
        }

    }

}