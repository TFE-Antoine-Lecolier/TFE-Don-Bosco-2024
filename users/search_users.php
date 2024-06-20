<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche d'Utilisateurs</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Recherche d'Utilisateurs</h2>
        <!-- Formulaire de recherche d'utilisateur -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="searchUser">Rechercher un utilisateur:</label>
                <input type="text" id="searchUser" name="searchUser" required>
                <button type="submit">Rechercher</button>
            </div>
        </form>
        <!-- Suggestions d'utilisateurs -->
        <div id="suggestions"></div>
        <script>
            $(document).ready(function(){
                $('#searchUser').keyup(function(){
                    var query = $(this).val();
                    if(query != ''){
                        $.ajax({
                            url:"suggest_users.php",
                            method:"POST",
                            data:{query:query},
                            success:function(data){
                                $('#suggestions').fadeIn();
                                $('#suggestions').html(data);
                            }
                        });
                    }
                });
                $(document).on('click', 'li', function(){
                    $('#searchUser').val($(this).text());
                    $('#suggestions').fadeOut();
                });
            });
        </script>
    </div>
</body>
</html>
