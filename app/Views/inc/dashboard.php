<h1>Dashboard</h1>

<p>Welcome, <?= session()->get('username') ?>!</p>

<a href="<?= base_url('login/logout') ?>">Logout</a>