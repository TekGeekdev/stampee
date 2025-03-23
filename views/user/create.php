{{ include('layouts/header.php', {title:'Création compte'})}}
<main>
<section class="form ombre-permanent">
        <h2>Créer un compte</h2>
        <form method="post">
            <div>
                <label for="name">Nom</label>
                <input type="text" name="name" id="name"
                placeholder="Entrez votre nom" value="{{user.name}}">
                {% if errors.name is defined %}
                <span class="span-erreur"> {{errors.name}}</span>
                {% endif %}
            </div>
            <div>
                <label for="email">Courriel</label>
                <input type="email" name="email" id="email"
                placeholder="Entrez un email de type abc@gmail.com" value="{{user.email}}">
                {% if errors.email is defined %}
                <span class="span-erreur"> {{errors.email}}</span>
                {% endif %}
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password"
                    placeholder="Entrez un mot de passe qui contient des chiffres et des lettres">
                {% if errors.password is defined %}
                    <span class="span-erreur"> {{errors.password}}</span>
                {% endif %}
            </div>
            <input type="submit" value="Créer le compte" class="bouton">
        </form>
    </section>
</main>


{{ include('layouts/footer.php')}}
