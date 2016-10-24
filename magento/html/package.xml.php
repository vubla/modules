<?php
$path = '/var/vubla/modules/magento/1.4.0-1.7.x/';
$compath = $path.'/app/code/community';

$version = file_get_contents('/var/vubla/modules/magento/html/version.php');
echo'<?xml version="1.0"?>
<package>
    <name>Vubla_Search</name>
    <version>'.$version.'</version>
    <stability>stable</stability>
    <license uri="http://opensource.org/licenses/osl-3.0.php">OSL</license>
    <channel>community</channel>
    <extends/>
    <summary>Vubla Search Engine plugin for magento</summary>
    <description>This package gives you access to the search engine provided by Vubla. It gives you both spelling correction and synonym support for searches made by your customers. Additionally a new suggestion searcher is available to give your customers quick access to the products they wish to find.</description>
    <notes>First release for Magento Connect.&#xD;
Includes:&#xD;
Vubla Search Engine Interface, Vubla Suggestion Interface, setup of API user and role (except API-key), and integration with layered navigation by rewriting some of catalogsearch module.</notes>
    <authors><author><name>Vubla Development Team</name><user>vubla</user><email>info@vubla.com</email></author></authors>
    <date>'.date('Y-m-d').'</date>
    <time>'.date('H:i:s').'</time>
     <contents>
        <target name="magecommunity">
            <dir name="Vubla">
                <dir>
                    <dir name="Search">
                        <dir name="Block">
                            <file name="Autocomplete.php" hash="'. md5_file($compath.'/Vubla/Search/Block/Autocomplete.php').'" />
                            <file name="Logo.php" hash="'. md5_file($compath.'/Vubla/Search/Block/Logo.php').'" />
                        </dir>
                        <dir name="Helper">
                            <file name="Data.php" hash="'. md5_file($compath.'/Vubla/Search/Helper/Data.php').'" />
                            <file name="Url.php" hash="'. md5_file($compath.'/Vubla/Search/Helper/Url.php').'" />
                        </dir>
                        <dir name="Model">
                            <dir name="Resource">
                                <dir name="Fulltext">
                                    <file name="Collection.php" hash="'. md5_file($compath.'/Vubla/Search/Model/Resource/Fulltext/Collection.php').'" />
                                </dir>
                                <file name="Setup.php" hash="'. md5_file($compath.'/Vubla/Search/Model/Resource/Setup.php').'" />
                                <dir name="Suggestion">
                                    <file name="Collection.php" hash="'. md5_file($compath.'/Vubla/Search/Model/Resource/Suggestion/Collection.php').'" />
                                </dir>
                            </dir>
                            <file name="Suggestion.php" hash="'. md5_file($compath.'/Vubla/Search/Model/Suggestion.php').'" />
                            <file name="Api.php" hash="'. md5_file($compath.'/Vubla/Search/Model/Api.php').'" />
                        </dir>
                        <dir name="etc">
                            <file name="config.xml" hash="'. md5_file($compath.'/Vubla/Search/etc/config.xml').'" />
                            <file name="api.xml" hash="'. md5_file($compath.'/Vubla/Search/etc/api.xml').'" />
                        </dir>
                        <dir name="sql">
                            <dir name="vubla_setup">
                                <file name="install-'.$version.'.php" hash="'. md5_file($compath.'/Vubla/Search/sql/vubla_setup/install-'.$version.'.php').'" />
                                <file name="mysql4-install-'.$version.'.php" hash="'. md5_file($compath.'/Vubla/Search/sql/vubla_setup/mysql4-install-'.$version.'.php').'" />
                            </dir>
                        </dir>
                    </dir>
                </dir>
            </dir>
        </target>
        <target name="mageetc">
            <dir name="modules">
                <file name="Vubla_Search.xml" hash="'. md5_file($path.'/app/etc/modules/Vubla_Search.xml').'" />
            </dir>
        </target>
        <target name="mageweb">
            <dir name="js">
                <dir name="vubla">
                    <file name="suggestion.js" hash="'. md5_file($path.'/js/vubla/suggestion.js').'" />
                </dir>
            </dir>
        </target>
        <target name="magedesign">
            <dir name="frontend">
                <dir name="base">
                    <dir name="default">
                        <dir name="template">
                            <dir name="vubla">
                                <file name="form.mini.phtml" hash="'. md5_file($path.'/app/design/frontend/base/default/template/vubla/form.mini.phtml').'" />
                                <file name="logo.phtml" hash="'. md5_file($path.'/app/design/frontend/base/default/template/vubla/logo.phtml').'" />
                            </dir>
                        </dir>
                        <dir name="layout">
                            <file name="vublasearch.xml" hash="'. md5_file($path.'/app/design/frontend/base/default/layout/vublasearch.xml').'" />
                        </dir>
                    </dir>
                </dir>
            </dir>
        </target>
        <target name="magelocale">
            <dir name="da_DK">
                <file name="Vubla_Search.csv" hash="'. md5_file($path.'/app/locale/da_DK/Vubla_Search.csv').'" />
            </dir>
            <dir name="en_US">
                <file name="Vubla_Search.csv" hash="'. md5_file($path.'/app/locale/en_US/Vubla_Search.csv').'" />
            </dir>
        </target>
    </contents>
     <compatible/>
    <dependencies><required><php><min>5.3.10</min><max>6.0.0</max></php><package><name>Catalog_Search</name><channel>core</channel><min></min><max></max></package><package><name>Catalog</name><channel>core</channel><min></min><max></max></package></required></dependencies>
</package>';
