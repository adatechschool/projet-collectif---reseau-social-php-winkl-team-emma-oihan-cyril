<?php
    session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
        <meta name="author" content="Julien Falconnet">
            <!-- Bootstrap CSS -->
       <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->

<style type="text/css">
  /* .jumbotron {
      width: 60%;
      margin: auto;
      text-align: center;
      background-color: #f9f9 ;
  }
  #output {
      border: 2px solid black;
      min-height: 60px;
      text-align: right;
      font-weight: bold;
      font-size: 20px;
      background-color: rgb(239, 247, 88) ;
  } */

  .btn {
      min-width: 100px;
      border: 2px solid black;
      text-align: center;
      right: 25px;
      margin: 2px;
      background-color: #f9f9 ;
  }
</style>
        <link rel="stylesheet" href="style.css"/>

    </head>
    <body>
        <?php
        include "menu.php";
        ?>
        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        toutes les utilisatrices du site.</p>
                </section>
            </aside>
            <main>           
                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                // Etape 1: Ouvrir une connexion avec la base de donnée.
                // $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                //verification
                // if ($mysqli->connect_errno)
                // {
                //     echo "<article>";
                //     echo("Échec de la connexion : " . $mysqli->connect_error);
                //     echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                //     echo "</article>";
                //     exit();
                // }
                include "dbconnect.php";
                
                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte, 
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    
                    posts.id,
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 500
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }


                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    //la ligne ci-dessous doit etre supprimée mais regardez ce 
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                    //echo "<pre>" . print_r($post, 1) . "</pre>";

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                    // on vous met le pied à l'étrier avec created
                    // 
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                       <address> <a href= "wall.php?user_id=<?php echo $post['id']?>"><?php echo $post['author_name']?></a>
                            <!-- <div class="col-12"> -->
                            <!-- Permet de savoir si le suiveur et le suivi sont identique ou pas -->
                            <?php $laQuestionEnSql2 = "
                            SELECT * FROM followers 
                            WHERE following_user_id =".  $_SESSION['connected_id']. "AND followed_user_id =" .$post['id'] ."
                            LIMIT 1";
                            
                            //si on suit qq ça retourne true
                            $lesInformations2 = $mysqli->query($laQuestionEnSql2); //query sert à transformer des strings en appel vers la base de données
                            // $lesInformations = true;
                            if ( ! $lesInformations2)
                            {
                                // echo "<article>";
                                // echo("Échec de la requete : " . $mysqli->error);
                                // echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>"); ?>
                                <form method="post" action="news.php">
                                    <!-- class="btn btn-success"  -->
                                    <input type="hidden" name="suivi" value="true" />
                                    <input type="button" value="Suivi" />
                                </form>
                            <?php 
                            }   else { ?>
                                    <form method="post" action="news.php">
                                        <input type="hidden" name="suivre" value="true" />
                                        <input type="button" value="Suivre" />
                                    </form>
                                    <?php if ($_POST['suivre'] != "true") { 
                                        $lInstructionSql = "INSERT INTO followers "
                                        . "(id, followed_user_id, following_user_id) "
                                        . "VALUES (NULL, "
                                        . $post['id'] . ", "
                                        . $_SESSION['connected_id'] .")"; // fin sql
                                        ;  // fermer php
                                  
                                        // Etape 5 : execution
                                        $ok = $mysqli->query($lInstructionSql);
                                        if ( ! $ok)
                                        {
                                            echo "Impossible de suivre cette personne : " . $mysqli->error;
                                        } else
                                        {
                                            echo " Vous suivez cette personne :" . $post['author_name'];
                                            //exit();
                                        } ?>  <!--fin de else -->
                                    <?php }  // fin de if post
                                } ?> <!--fin de else -->
                  
                        </address>
                        <div>
                            <p> <?php echo $post['content']?></p>
                        </div>
                        <footer>
                     

                            <small> <form method="post" action="news.php">
                                        <input type="hidden" name="likes" value="true" />
                                        <input type="button" value="♥ <?php echo $post['like_number']?>" />
                                    </form> 
                                     
                            </small>
                            <!-- // Faire par ordre de difficulté : 1-Liker à l'infini
                            // 2-Liker une seule fois!
                            // 3-Unliker! -->
                            <?php if ($_POST['likes'] = "true") { 
                                
                                        $lInstructionSql = "INSERT INTO likes "
                                        . "(id, user_id, post_id) "
                                        . "VALUES (NULL, "
                                        . $_SESSION['connected_id'] . ", "
                                        . $post['id'] .")" // fin sql
                                        ;  // fermer php
                                       // echo $lInstructionSql;

                                        // Etape 5 : execution
                                        $ok = $mysqli->query($lInstructionSql);
                                        if ( ! $ok)
                                        {
                                            echo "Impossible de liker cette personne : " . $mysqli->error;
                                        } else
                                        {
                                            //echo " Vous likez cette personne : " . $post['author_name'];
                                            //exit();
                                        } ?>  <!--fin de else -->
                             <?php }  ?> <!-- fin de if post -->

                            <a href="">#<?php echo $post['taglist']?></a>,
                        </footer>
                    </article>
                    <?php } ?>  <!--fin de while -->

            </main>
        </div>
    </body>
</html>