{{ include('layouts/header.php', {title:'Connexion au compte'})}}
<main>
    <section class="form">
        <h2>Connexion à votre portail de membre</h2>
        <form method="post">
            <div>
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username"
                    value="{{user.username}}">
                    {% if errors.username is defined %}
                    <span class="span-erreur">{{errors.username}}</span>
                {% endif %}
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
                {% if errors.password is defined %}
                    <span class="span-erreur"> {{errors.password}}</span>
                {% endif %}
            </div>
            <input type="submit" value="Connexion" class="bouton">
            {% if errors.message is defined %}
                    <span class="span-erreur"> {{errors.message}}</span>
                {% endif %}
        </form>
    </section>
</main>
{{ include('layouts/footer.php')}}
