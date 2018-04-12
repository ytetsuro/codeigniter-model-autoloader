# CodeIgniter Model Autoload Hook

CodeIgniter Model Loadable hook

## Setup

```shell
$ cp hooks/CILoaderUseModelAutoloader.php /path/to/codeigniter-project/application/hooks/
```

add config/hook.php

```php
<?php

$hook['pre_controller'][] = [
    'class'    => 'CILoaderUseModelAutoloader',
    'function' => 'register',
    'filename' => 'CILoaderUseModelAutoloader.php',
    'filepath' => 'hooks',
    'params'   => [],
];
$hook['post_controller_constructor'][] = [
    'class'    => 'CILoaderUseModelAutoloader',
    'function' => 'unregister',
    'filename' => 'CILoaderUseModelAutoloader.php',
    'filepath' => 'hooks',
    'params'   => [],
];
```

## Run

```
<?php

class Welcome extends CI_Controller
{

	public function index()
	{
		$sample_model = new Sample_model();
	}
}

```

## LICENCE

NYSL
