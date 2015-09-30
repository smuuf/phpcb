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
				margin: 0;
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

			p, header {
				margin: 10px 0;
				padding: 0;
			}

			.engine {
				display: inline-block;
				font-size: 10pt;
				padding: 5px;
				margin: 0;
				color: #333;
				background: #eee;
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

			.result-colorbox span.score2 {
				padding: 5px;
				height: 100%;
				color: #fff;
				opacity: 0.75;
				background: rgba(0,0,0,0.25);
				text-shadow: 0px 1px 0px #000;
			}

		</style>
	</head>
	<body>
		<header>
			<h1><?= $tpl['app_name']; ?> results</h1>
			<h2>PHP <?= phpversion(); ?></h2>
		</header>

		<p><span class="engine">Engine used: <b><?= $tpl['engine_used']; ?></b></span></p>
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

					// Avoid division by zero in certain cases:
					// 1) Total time is below measurable threshold
					// 2) Minimal and maximal of the benchmard time are the same
					// .. (ie. there's only one test or there are no differences)
					if ($tpl['total_time'] && $tpl['min_time'] - $tpl['max_time']) {

						$score = $tpl['min_time'] / $resultTime * 100;
						$score2 = $resultTime / $tpl['min_time'];

					} else {

						$score2 = false;
						$score = false;

					}

				?>

				<p><b>Closure <?= $index + 1 ?></b> \ Code:</p>
				<pre class="code"><?= htmlentities($tpl['source_function']($closure), ENT_HTML5); ?></pre>

				<div class='result-colorbox' style='background-color: <?= $score !== false ? $tpl['gradient_function']($score) : '#888'; ?>'>
					<span class="score">Score: <b><?= $score !== false ? number_format($score, 2) : 'N/A'; ?> %</b></span>
					<?php if ($score2 != 1): ?>
						<span class="score2"><?= $score2 !== false ? number_format($score2, 2) : 'N/A'; ?> &times; slower</span>
					<?php endif; ?>
				</div>

			</li>
		<?php endforeach; ?>
		</ul>

	</body>
</html>
