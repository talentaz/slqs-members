<?php 
if($member->pdf_path) {
    ?>

<a href="<?php echo esc_html($member->pdf_path); ?>" class="btn" target="_blank">View Membership Card</a>

<?php
} else {
?>
<p>There is no membership card.</p>
<?php
    }
?>