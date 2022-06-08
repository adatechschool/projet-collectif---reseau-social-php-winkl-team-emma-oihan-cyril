//Ajouter un formulaire d’abonnement sur la page wall.php quand ce n’est la page de l’utilisateur connecté 
// (en gros l’utilisateur peut s’abonner à un auteur sur le mur de cet auteur).
// Tant que l'utilisateur est sur une page qui n'est pas la sienne il est en mesure de s'abonner/liker/follower la page.
// pour pouvoir liker/follower il faut: 
// que le bouton réponde (c'est à dire que le fait de cliquer sur le bouton déclenche une fonction)
// fonction follow
// prendre le user id de la personne suivie et du suiveur
// ajouter/injecter les deux infos dans la BDD à l'aide de SQL (INSERT)
// trouver la longueur de la liste des followers pour l'afficher sur le profil de la personne
// permettre d'afficher la liste des followers.
// comparer les noms pour savoir à qui est le compte: si compte courant est différent du compte affiché alors ne pas afficher de bouton suivre.


$lInstructionSql = "INSERT INTO followers "
                                . "(id, followed_user_id, following_user_id) "
                                . "VALUES (NULL, "
                                . $post['id'] . ", "
                                . "'" . $_SESSION['connected_id'];" // fin sql
                                ;  // fermer php

                        //echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible de suivre cette personne : " . $mysqli->error;
                        } else
                        {
                            echo " Vous suivez cette personne :" . $post['author_name'];
                            //exit();
                        }
                    }