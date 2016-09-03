<?php
/**
 * MagicCards.info get sets
 * Get mtg sets list from http://magiccards.info in En, Es and Pt
 * @author Vitor de Toledo Furlin (vtfurlin) <vitor@furlin.me>
 * @copyright 2016 Vitor de Toledo Furlin
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once 'assets/web_scraping.inc';
error_reporting(E_ALL);
ini_set('display_errors', true);
try
{
  $Xpath = getXpath(cURL('http://magiccards.info/sitemap.html'));

  $sets = [];
  //table[2] = en, table[6] = es, table[7] = pt
  foreach (query($Xpath, '/html/body/table[2]/tr/td/ul/li') as $set_list)
    foreach (query($Xpath, './ul/li', $set_list) as $set)
      $sets[trim(query($Xpath, './small', $set)->item(0)->nodeValue)]['en'] = trim(query($Xpath, './a', $set)->item(0)->nodeValue);

  foreach (query($Xpath, '/html/body/table[6]/tr/td/ul/li') as $set_list)
    foreach (query($Xpath, './ul/li', $set_list) as $set)
      $sets[trim(query($Xpath, './small', $set)->item(0)->nodeValue)]['es'] = trim(query($Xpath, './a', $set)->item(0)->nodeValue);

  foreach (query($Xpath, '/html/body/table[7]/tr/td/ul/li') as $set_list)
    foreach (query($Xpath, './ul/li', $set_list) as $set)
      $sets[trim(query($Xpath, './small', $set)->item(0)->nodeValue)]['pt'] = trim(query($Xpath, './a', $set)->item(0)->nodeValue);

} catch (Exception $e)
{
  //handle cURL / Xpath Exceptions
  var_dump($e);
}

//get gatherer sets

try
{
  $Xpath = getXpath(cURL('http://gatherer.wizards.com/Pages/Default.aspx'));

  $gatherer_sets = [];

  foreach (query($Xpath, '//*[@id="ctl00_ctl00_MainContent_Content_SearchControls_setAddText"]/option') as $set)
    $gatherer_sets[] = trim($set->nodeValue);

  //echo array_search('Shadows over Innistrad', $sets);

  foreach ($sets as $k => $v)
  {
    if(array_key_exists('en', $sets[$k]))
      if(($key = array_search($sets[$k]['en'], $gatherer_sets)) !== false)
      {
        unset($gatherer_sets[$key]);
        unset($sets[$k]);
      }
  }
} catch (Exception $e)
{
  //handle cURL / Xpath Exceptions
  var_dump($e);

}

echo '<pre>';

foreach ($sets as $s)
  if(array_key_exists('en', $s))
    echo $s['en'].'<br />';

echo '<br /><br />';

foreach ($gatherer_sets as $s)
  echo $s.'<br />';
