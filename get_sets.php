<?php
/**
 * MagicCards.info get sets
 * Get mtg sets list from http://magiccards.info in En, Es and Pt
 * @author Vitor de Toledo Furlin (vtfurlin) <vitor@furlin.me>
 * @copyright 2016 Vitor de Toledo Furlin
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

require_once 'assets/web_scraping.inc';

try
{
  $Xpath = getXpath('http://magiccards.info/sitemap.html');
} catch (Exception $e)
{
  //handle cURL / Xpath Exceptions
}

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

//get gatherer sets

try
{
  $Xpath = getXpath('http://gatherer.wizards.com/Pages/Default.aspx');
} catch (Exception $e)
{
  //handle cURL / Xpath Exceptions
}

$gatherer_sets = [];

foreach (query($Xpath, '//*[@id="ctl00_ctl00_MainContent_Content_SearchControls_setAddText"]/option') as $set)
  $gatherer_sets[] = trim($set->nodeValue);

//echo array_search('Shadows over Innistrad', $sets);

echo '<pre>';
foreach ($sets as $k => $v)
{
  if(($key = array_search($sets[$k]['en'], $gatherer_sets)) !== false)
  {
    unset($gatherer_sets[$key]);
    unset($sets[$k]);
  }
}

foreach ($sets as $s) {
  echo $s['en'].'<br />';
}
echo '<br /><br />';

foreach ($gatherer_sets as $s) {
  echo $s.'<br />';
}
