<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= $this->title; ?></title>
        <meta name="description" content="TinyPHP Skeleton Application" />
	<?= $this->getJavascripts(); ?>
	<?= $this->getStylesheets(); ?>
</head>
<body>
    <?= $this->content; ?>
</body>
</html>