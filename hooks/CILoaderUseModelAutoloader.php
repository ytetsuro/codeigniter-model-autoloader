<?php
/**
 * CodeIgniterのModelをLoaderを利用したAutoloadを行う
 */
class CILoaderUseModelAutoloader
{
	/**
	 * @var CI_Loader
	 */
	private $loader;

	/**
	 * @var array
	 */
	private $loaded_paths = [];

	/**
	 * autoloaderを登録する
	 * 
	 * @return void
	 */
	public function register()
	{
		spl_autoload_register([$this, 'autoload']);
	}

	/**
	 * autoloaderを削除する
	 * 
	 * @return void
	 */
	public function unregister()
	{
		spl_autoload_unregister([$this, 'autoload']);
	}

	/**
	 * Loaderクラスが利用可能か判定する
	 * 
	 * @return bool
	 */
	public function is_usable_loader()
	{
		if ($this->loader) {
			return true;
		} elseif (in_array(is_loaded(), 'Loader', TRUE)) {
			$this->loader =& load_class('Loader');
			return true;
		}

		return false;
	}

	/**
	 * オートロードする
	 * 
	 * @param string $class_name 
	 * 
	 * @return void
	 */
	public function autoload($class_name)
	{
		if (!$this->is_usable_loader()) {
			return;
		}

		if (isset($this->loaded_paths[$class_name])) {
			return;
		}

		$loadable_model_path = $this->find_loadable_model_path($class_name);

		if ($loadable_model_path) {
			$this->loaded_paths[$class_name] = $loadable_model_path;
			$this->loader->model($loadable_model_path);
		}
	}

	/**
	 * Loaderクラスでロード可能なModelのパスを取得する
	 * 
	 * @todo: 責任範囲が違うのでFinderクラスを実装したい。
	 * 
	 * @param string $class_name 
	 * 
	 * @return string
	 */
	private function find_loadable_model_filepath($class_name)
	{
		$ci_model_base_paths = $this->loader->get_package_paths();
		$loadable_model_path = '';
		$model_basename      = $class_name . '.php';

		foreach ($ci_model_base_paths as $base_path) {
			$model_full_path = $this->find_model_file($base_path, $model_basename);

			if ($model_full_path !== '') {
				// ディレクトリがマルチバイトの可能性を考えてmb_strlenでファイル名をLoaderクラスでロード可能なパスにする
				$loadable_model_path = substr($model_full_path, mb_strlen($base_path));
				break;
			}
		}

		return $loadable_model_path;
	}

	/**
	 * モデルのファイルパスを取得する
	 * 
	 * @todo: 責任範囲が違うのでFinderクラスを実装したい。
	 * 
	 * @param string $find_dir 
	 * @param string $model_basename 
	 * 
	 * @return string
	 */
	private function find_model_filepath($find_dir, $model_basename)
	{
		$find_files = glob(rtrim($find_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*');
		$relative_path = '';

		foreach ($find_files as $file_path) {
			if (is_file($file_path) && basename($file_path) === $model_basename ) {
				$relative_path = substr($file_path, 0, strlen($model_basename)) . strtolower($model_basename);
				break;
			} elseif ( is_dir($file) && $relative_path = $this->find_model_file($find_dir. DIRECTORY_SEPARATOR . $file_path, $model_basename)) {
				break;
			}
		}

		return $relative_path;
	}
}
