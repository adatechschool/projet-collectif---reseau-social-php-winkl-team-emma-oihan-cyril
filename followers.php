<?php
    session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php
        include "menu.php";
    
        ?>
        <div id="wrapper">  
        <?php
            /**
             * Cette page est TRES similaire à wall.php. 
             * Vous avez sensiblement à y faire la meme chose.
             * Il y a un seul point qui change c'est la requete sql.
             */
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             */
            $userId = intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            // $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
            include "dbconnect.php";
            ?>        
            <aside>
            <?php
                $laQuestionEnSql = "SELECT * FROM `users` WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                ?>
                <img src = "user.jpg" alt = "Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice <?php echo $user['alias']?>
                        (n° <?php echo intval($_GET['user_id']) ?>)
                    </p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
               // $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                // $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                //include "dbconnect.php";
                
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                while ($post = $lesInformations->fetch_assoc())
                {
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
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
