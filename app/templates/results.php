<!doctype html>
<html>
	<head>
		<title>
			<?= $tpl['app_name']; ?>
		</title>
		<style>

			body {
				font-family: Helvetica, Arial, sans-serif;
				font-size: 10pt;
			}

			h1 {
				display: inline-block;
				font-size: 18pt;
				padding: 10px;
				margin: 0;
				color: #fff;
				background: #888;
			}

			h2 {
				display: inline-block;
				font-size: 18pt;
				margin: 10px 0;
				color: #000;
			}

			h3 {
				display: inline-block;
				margin: 0;
				font-size: 16pt;
				font-family: Arial;
				padding: 0 5px 5px 5px;
				color: #fff;
				background: #333;
			}

			p {
				margin: 10px 0;
				padding: 0;
			}

			.results{
				list-style: none;
				margin: 0; padding: 0;
			}

			.results li{
				margin: 10px 0 10px 0;
				border-top: 5px solid #333;
			}

			.results li:nth-child(even){
				background: rgba(0,0,0,0.05);
			}

			pre.code {
				font-size: 10pt;
				background: #ddd;
				padding: 10px;
				margin: 0;
				font-family: Consolas, Courier, monospace;
			}

			.result-time {
				font-size: 10pt;
				font-weight: bold;
				color: #FF1F47;
				margin-bottom: 10px;
			}

			.result-time-single {
				font-size: pt;
				color: #888;
			}

			.result-colorbox{
				padding: 10px 10px 10px 5px;
				border-bottom: 5px solid rgba(0,0,0,0.25);
			}

			.result-colorbox span.score {
				background: rgba(255,255,255,0.75);
				padding: 5px;
				height: 100%;
			}

			.result-colorbox span.of-total {
				padding: 5px;
				height: 100%;
				color: #fff;
				opacity: 0.75;
			}

		</style>
	</head>
	<body>
		<h1><?= $tpl['app_name']; ?> results</h1>
		<h2>PHP <?= phpversion(); ?></h2>

		<p>Iterations: <?= $tpl['count']; ?>x \ Total time: <?= number_format($tpl['total_time'], 4); ?> ms</p>

		<ul class="results">

		<?php
			$position = 0;
			foreach($tpl['results'] as $index => $resultTime):
		?>
			<li class="single-result">

				<h3><?= ++$position; ?>.</h3>

				Result:
				<span class='result-time'><?= number_format($resultTime, 4); ?> ms</span> \
				<span class='result-time-single'><?= bcdiv($resultTime, $tpl['count']); ?> ms</span> per single iteration

				<?php
					$closure = $tpl['closures'][$index];
					$percentageOfTotal = $resultTime / $tpl['total_time'] * 100;
					$score = ($tpl['results'][$index] - $tpl['max_time']) / ($tpl['min_time'] - $tpl['max_time']) * 100;
				?>

				<p><b>Closure <?= $index + 1 ?></b> \ Code:</p>
				<pre class="code"><?= htmlentities($tpl['source_function']($closure), ENT_HTML5); ?></pre>

				<div class='result-colorbox' style='background-color: <?=$tpl['gradient_function']($score); ?>'>
					<span class="score">Score: <b><?= number_format($score, 2); ?> %</b></span> <span class="of-total"><?= number_format($percentageOfTotal, 2); ?> % of total</span>
				</div>

			</li>
		<?php endforeach; ?>
		</ul>

	</body>
</html>
