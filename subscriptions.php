<?php
    session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnements</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php
        include "menu.php";
        $userId = intval($_GET['user_id']);
            /**
             * Etape 2: se connecter à la base de donnée
             */
            // $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            include "dbconnect.php";
            ?>
        <div id="wrapper">
            <aside>
            <?php
                /**
                 * Etape 3: récupérer le nom du mot-clé
                 */
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
               // echo "<pre>" . print_r($user, 1) . "</pre>";
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>


                    <p>Sur cette page vous trouverez la liste des personnes dont
                        l'utilisatrice <?php echo $user['alias']?>
                        n° <?php echo intval($_GET['user_id']) ?>
                        suit les messages
                    </p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                // $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
              //  include "dbconnect.php";
                
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.* 
                    FROM followers 
                    LEFT JOIN users ON users.id=followers.followed_user_id 
                    WHERE followers.following_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                while ($post = $lesInformations->fetch_assoc())
                {
                    //echo "<pre>" . print_r($post, 1) . "</pre>"
               ?>
                <article>
                    <img src="user.jpg" alt="blason"/>
                    <h3><a href= "wall.php?user_id=<?php echo $post['id']?>"><?php echo $post['alias']?></h3>
                    <p><a href= "wall.php?user_id=<?php echo $post['id']?>"><?php echo $post['id']?></p>                    
                </article>
                <?php
                }
                ?>
            </main>
        </div>
    </body>
</html>
