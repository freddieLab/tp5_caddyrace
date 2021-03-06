<?php

    // on se connecte à notre base de données
    try
    {
        $bdd = new PDO('mysql:host=db5000026047.hosting-data.io;dbname=dbs21225;charset=utf8', 'dbu34731', 'Fred&200172', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    //**************************************************************************************
    // => Frontend: listView jQuery - Autocomplete search item      
    //**************************************************************************************
 
    $reponse = $bdd->prepare('SELECT * FROM items_priv WHERE item_priv_purchase = 0 AND item_priv_owner_id = :member_id AND item_priv_name LIKE :req'); 
    $reponse->execute(array(
        'member_id' => $_GET['sessionId'],
        'req' => '%'.$_GET['req'].'%'
    )); 
    $liste = array(); 
 
    while($donnees = $reponse->fetch()) { 

    // formatage de l'affichage des données de la liste 
        $liste[] = $donnees; 
    } 
    $items = $liste;
    $req = trim(strip_tags($_GET['req']));
    $matches = array();    
    foreach($items as $item) {
        $item['value'] = $item['item_priv_name'];
        $item['label'] = "{$item['item_priv_name']}";
        $matches[] = $item;
    }
 
    echo json_encode($matches);      
?>