<h2 class="center">Tous les utilisateurs</h2>
<a href="/">Home Page</a>
<a href="/profil">Profile Page</a>

<div class="form">
    <h2>Modifier les utilisateurs</h2>
    <?php foreach ($users as $user): ?>
        <div class="flex">
            <h2><?= $user['name'] ?></h2>
            <p><?= $user['email'] ?></p>
            <p>id :<?= $user['id'] ?></p>
            <form action="/deleteUser" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input class="btn btn_red" type="submit" value="Supprimer">
            </form>
        </div>
    <?php endforeach; ?>
</div>