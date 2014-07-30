<p class="title">تلتزم الشركة بتامين المشتركين وفق القوانين التالية :</p>
<ul>
    <?php
    $config = $data['config'];
    foreach ($config as $c) 
    {
        echo "<li>".$c['law'].$c['value']."</li>";
    }
    ?>
    
    <p></p>في حال وجود اي استفسار يرجى الاتصال بنا بالضط <a href='<?php echo BASE_URL."controllers/contact.php" ;?>'>هنا</a></p>
</ul>