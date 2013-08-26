<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="/universoideas/js/s3Slider.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#slider1').s3Slider({
            timeOut: 4000 
        });
    });
</script>

<div id="slider1">
    <ul id="slider1Content" style=" margin:0 0 0 -40px">
        <?php 
            foreach ($articles_gallery as $article) { 
                $img = "";
                $vid = "";

                $img = $article['img']['image_id'];
                $date = $this->Time->format('D-F-j-Y-h:i A', $article['art']['modified']);
                list($dia_sem, $mes, $dia, $ano) = explode('-', $date);

                echo "<li class='slider1Image'>";
                echo "<input id='article_id' type='hidden' value='" . $article['art']['id'] . "'/>";
                echo "<a href='/universoideas/pages/article?id=" . $article['art']['id'] . "'>";
                echo $this->Html->image($article['img']['uri'], array('width' => '550', 'height' => '266'));
                
                echo "<span class='gright'>";
                echo "<strong>" . $article['art']['title'] . "</strong><br/>";
                echo "<div class='dia'>" . __($dia_sem) . " " . __($mes) . " " . __($dia) . ", " . __($ano) . "</div>";
                echo $article['art']['summary'];
                echo "</span>";
                
                echo "</a>";
                echo "</li>";
            }
        ?>
        <div class="clear slider1Image"></div>
    </ul>
</div>