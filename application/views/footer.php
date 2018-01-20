
</div>

<style>
#hidden-menu a{
//color:<?=CC_COLOR?>;
}
#hidden-menu a:hover{
text-decoration:underline;
}
</style>

<footer class="w3-border-top w3-small w3-center" style="">
    <p>&copy; No copyright, use this content as you wish without any guarantee to be fit for any purpose. Join us on our <a class="w3-text-green" href="https://chat.whatsapp.com/HWBwUkFj0eqG8eAT9XhGfC" target="_blank"><i class="fa fa-whatsapp"></i> WhatsApp group</a>.</p>
    <p>Developed by <a class="w3-text-<?=CC_COLOR?>" href="https://m.me/snehanshuphukon" target="_blank">Snehanshu Phukon</a> and <a class="w3-text-<?=CC_COLOR?>" href="https://fb.me/ianupamsaikia" target="_blank">Anupam Saikia</a></p>

    <span class="w3-right" id="hidden-menu">
     
    <?php if (isset($auth_user_id)):?>
        Hi, <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('user/'.urlencode($auth_username))?>"><?=htmlspecialchars($auth_username)?></a> (<?=$auth_level?>),
        [<a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('user/logout')?>">Logout</a>]&nbsp;

        <?php if ($auth_level >= 6):?>

            New <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('unit/new')?>">Unit</a>, 
            <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('note/new')?>">Note</a>, 
            <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('teacher/new')?>">Teacher</a>, 
            <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('subject/new')?>">Subject</a>
            <?php if ($auth_level >= 9):?>
            , <a class="w3-text-<?=CC_COLOR?>" href="<?=site_url('user/new')?>">User</a>
            <?php endif;?>
        <?php endif;?>
    <?php else:?>
        <a class="w3-hover-text-black" style="color:#eee;" href="<?=site_url('login?redirect='.urlencode(URI_STRING))?>" rel="nofollow">Login</a>
    <?php endif;?>

    </span>
</footer>

</div>
</body>
</html>