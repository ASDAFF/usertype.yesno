<?
IncludeModuleLangFile(__FILE__);
Class site05_usertypeyesno extends CModule
{
	const MODULE_ID = 'site05.usertypeyesno';
	var $MODULE_ID = 'site05.usertypeyesno'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("site05.usertypeyesno_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("site05.usertypeyesno_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("site05.usertypeyesno_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("site05.usertypeyesno_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		//RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSiteUsertypeyesno', 'OnBuildGlobalMenu');
		RegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", self::MODULE_ID, "CUserTypeYesNo", "GetUserTypeDescription", 50);
		COption::SetOptionString(self::MODULE_ID, "prop_check", "Y");
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		//UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CSiteUsertypeyesno', 'OnBuildGlobalMenu');
		COption::SetOptionString(self::MODULE_ID, "prop_check", "N");
		UnRegisterModuleDependences("iblock", "OnIBlockPropertyBuildList", self::MODULE_ID, "CUserTypeYesNo", "GetUserTypeDescription");
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item,
					'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.self::MODULE_ID.'/admin/'.$item.'");?'.'>');
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/'.$item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
						continue;

					$dir0 = opendir($p0);
					while (false !== $item0 = readdir($dir0))
					{
						if ($item0 == '..' || $item0 == '.')
							continue;
						DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
					}
					closedir($dir0);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		//COption::SetOptionString(self::MODULE_ID, "prop_check", "N");
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
