<!doctype html>
<html lang="en">
<head>
	<title>Download YouTube Video with PHP</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<main>
	<h1 class="text-center mt-5">Download YouTube Video with PHP</h1>
	<form class="mt-3" name="ytForm" methor="get">
		<div class="mx-auto w-50">
			<div class="input-group">
				<input type="text" name="yt" value="<?php echo $_GET['yt'] ?? ''; ?>" placeholder="Youtube Video URL" class="form-control">
				<select name="quality" class="form-control" style="flex: 0 0 100px;">
					<?php foreach ( [ 360, 480, 720, 1440, 1080 ] as $quality ) : ?>
						<option value="<?php echo $quality; ?>"<?php echo isset( $_GET['quality'] ) && $_GET['quality'] == $quality ? ' selected="selected"' : ''; ?>><?php echo $quality; ?>p</option>
					<?php endforeach; ?>
				</select>
				<span class="input-group-btn">
					<button class="btn btn-primary rounded-right" type="submit">Download</button>
               </span>
			</div>
		</div>
	</form>
	
	<?php
	if ( isset( $_GET['yt'] ) ) : ?>
		<?php
		include __DIR__ . '/vendor/autoload.php';
		
		$yt_link = $_GET['yt'];
		
		try {
			$youtube = new pira\YTDL( $yt_link );
			$youtube->setQuality( $_GET['quality'] ?? 720 );
			$youtube->setFilenamePattern( 'basic' );
			$response = $youtube->sendRequest();
			?>
			<div class="video-wrapper mt-3 mb-5 mx-auto w-50" style="min-width: 480px">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title"></h5>
						<div class="row" style="padding: 0 15px;">
							<?php if ( $response['status'] === true && isset( $response['data']['url'] ) ) : ?>
								<a href="<?php echo $response['data']['url'] ?>" target="_blank" class="btn btn-primary" style="width: 200px;">
									Download
								</a>
								<script>
									fetch( 'https://noembed.com/embed?url=<?php echo $yt_link; ?>' )
										.then( function ( response ) {
											return response.json();
										} )
										.then( function ( data ) {
											console.log( data );
											if ( data.thumbnail_url ) {
												document.querySelector( '.card' ).insertAdjacentHTML( 'afterbegin', '<img class="card-img-top" src="' + data.thumbnail_url + '"/>' );
											}
											
											if ( data.thumbnail_width ) {
												document.querySelector( '.video-wrapper' ).style.width = data.thumbnail_width + 'px !important';
											}
											
											if ( data.title ) {
												document.querySelector( '.card-title' ).innerText = data.title;
											}
										} )
								</script>
							<?php else: ?>
								<p>No links found</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		} catch ( Exception $e ) {
			echo 'Something went wrong: ' . $e->getMessage();
		}
		?>
	<?php endif; ?>
</main>
</body>
</html>
