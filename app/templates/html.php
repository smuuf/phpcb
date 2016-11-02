<!doctype html>
<html>
	<head>
		<title><?= $tpl['app_name']; ?></title>
		<link rel="stylesheet" type="text/css" href="<?=$tpl['css_file'];?>" media="all">
	</head>
	<body>
		<header id="main-header">
			<h1><?= $tpl['app_name']; ?></h1>
			<span class="separator">\\\</span>
			<h2>PHP <?= phpversion(); ?></h2>
		</header>

		<section id="sub-header">
			<span class="item">Engine used: <span class="value"><?= $tpl['engine_used']; ?></span> </span> <span class="separator">\\\</span>
			<span class="item">Total time: <span class="value"><?= number_format($tpl['total_time'], 4); ?></span> ms</span> <span class="separator">\\\</span>
			<span class="item">Iterations: <span class="value"><?= number_format($tpl['count'], 0, '.', ' '); ?></span> x </span>
		</section>

		<ul class="results">

		<?php
			$position = 0;
			foreach($tpl['results'] as $index => $resultTime):
		?>
			<li class="single-result">

				<?php

					$closure = $tpl['closures'][$index];

					// Avoid division by zero in certain cases.
					if ($tpl['total_time'] && $resultTime && $tpl['min_time'] && $tpl['min_time'] - $tpl['max_time']) {

						$score = $tpl['min_time'] / $resultTime * 100;
						$score2 = $resultTime / $tpl['min_time'];

					} else {

						$score2 = false;
						$score = false;

					}

				?>

				<header>
					<div class="score" style="color: <?= $score !== false ? $tpl['gradient_function']($score) : '#333'; ?>">
						<?= $score !== false ? floor($score) : 'N/A'; ?><!--
					--><span class='fraction' style="color: <?= $score !== false ? $tpl['gradient_function']($score, 100) : '#888'; ?>"><!--
						--><?= $score !== false ? '.' . sprintf('%02d', ($score - floor($score)) * 100) : null; ?>
						</span>
					</div>
					<div class="info">
						<div class="group">
							<?php if ($score2 != 1): ?>
							<span class='value'>
									<?= $score2 !== false ? number_format($score2, 2) : 'N/A'; ?> &times;
							</span>
							<span class='label'>slower</span>
							<?php endif; ?>
						</div>
						<div class="group">
							<span class='value'><?= number_format($resultTime, 4); ?> ms</span>
							<span class='label'>cost</span>
						</div>
						<div class="group">
							<span class='value'><?= bcdiv($resultTime, $tpl['count']); ?> ms</span>
							<span class='label'>single iteration</span>
						</div>
					</div>
					<div class="position">
						<small>Closure</small> <?= $index + 1; ?>
					</div>
				</header>

				<pre class="code"><?= htmlentities($tpl['source_function']($closure)); ?></pre>

			</li>
		<?php endforeach; ?>
		</ul>

		<div class="github-link"><a href='<?=$tpl['github_link']?>' target='_blank'>Check out <b>phpcb</b> on GitHub</a></div>

	</body>
</html>
