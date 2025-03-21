{{ include('layouts/header.php', {title:'Compte utilisateur'})}}

<main class="userAccount">
    <section>
    <h2>Bienvenue {{user.name}}</h2>
    </section>
</main>

{{ include('layouts/footer.php')}}

