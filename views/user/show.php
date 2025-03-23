{{ include('layouts/header.php', {title:'Compte utilisateur'})}}

<main>
    <section class="userAccount"> 
    <h2>Bienvenue {{user.name}}</h2>
    <div class="boite-flex">
        <div class="userAccount-boite boite-flex is-column">
            <h3>Mes options</h3>
                <a href="{{base}}/user/edit" class="bouton">Modifier profil</a>
                <a href="{{base}}/stamp/create" class="bouton">Créer un timbre</a>
                <a href="{{base}}/stamp/index" class="bouton">Mes timbres</a>
                <a href="{{base}}/auction/create" class="bouton">Créer une enchère</a>
                <a href="{{base}}/auction/create" class="bouton">Mes enchères</a>
                <a href="#" class="bouton">Mes favoris</a>
        </div>
        <div>
            <h3>Mon Résumé</h3>
            <p>Nom utilisateur : {{user.username}}</p>
            <p>Courriel : {{user.email}}</p>
            <p>Timbres enregistrés : x</p>
            <p>Enchères à venir : x</p>
            <p>Enchères en cours : x</p>
            <p>Enchères en expirées : x</p>
        </div>
    </div>
    </section>
</main>

{{ include('layouts/footer.php')}}

