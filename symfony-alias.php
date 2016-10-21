<?php

require("config.php");
//On récupère les commande stocké en json
$commandStructure = (array) json_decode(file_get_contents($dirPath."/command.json"), true);

//On initialise la commande console à "php bin/console "
$command = $prefixCommand." ";
$unknownCommand = [];
$transit = $commandStructure;

//Si il y a au moins un argument
if(count($argv)>1){
  //On parcours les arguments
  for($i=1; $i<count($argv); $i++){

    //On test que l'argument courant ne soit pas celui par défaut
    if($argv[$i] != "default"){
      //Si c'est le dernier argument, on s'attend à utiliser à la prochaine itération;
      if(!isset($argv[$i+1])){
        $argv[$i+1] = "default";
      }
    }

    //Si un arguments existe dans les racourcis
    if(isset($transit[$argv[$i]])){
      //Si il est soumis à des précision
      //  && !$transit[$argv[$i]["notice"]]
      system("'key : ".$transit[$argv[$i]]."';");

      if(is_array($transit[$argv[$i]])){

        $transit = $transit[$argv[$i]];
      //Sinon on est au bout du tableau
      } else {
        $command .= $transit[$argv[$i]];

        //On a plus besoin de transit;
        $transit = null;
      }

    //Si l'argument n'existe pas dans les command ou que le tableau transit n'existe plus
    } else {
      //Si l'argument n'est pas un default
      if($argv[$i]!= "default"){
        //On le rajoute dans le tableau d'option
        array_push($unknownCommand, $argv[$i]);
      }
    }
  }
}

//On parcourt le tableau d'option en concaténant les options suplémentaire
for($i=0; $i<count($unknownCommand); $i++){
  $command.= " ".$unknownCommand[$i];
}

//On execute la commande
system($command);

?>
