<?php
$terms = get_terms( array(
	'taxonomy' => 'location',
	'parent'   => 0,
	'hide_empty' => 0,
) );
?>


<div class="waste-art-container">
	<div class="container">
		<div class="row">
			<?php foreach ($terms as $term): ?>
			<div class="col-md-4">
				<?php echo "<a class='waste-arts-item' href='#' data-term-id='$term->term_id'>$term->name</a>";?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

    <div class="container" id="wast-art-items">
        <h2 class="container-title"></h2>
        <div class="row">

        </div>
    </div>
</div>
