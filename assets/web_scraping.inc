<?php
/**
 * Furlin Web Scraping
 * Collection of functions to facilitate web scraping
 * @author Vitor de Toledo Furlin (vtfurlin) <vitor@furlin.me>
 * @copyright 2016 Vitor de Toledo Furlin
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

/**
 * uses cURL to get the content from a URL
 * @param string $url URL to get content from
 * @param array [$options] options to use with curl_setopt
 * @return string
 */
function cURL($url, $options = [])
{
  try
  {
    if(!$cURL_handle = curl_init())
      throw new Exception('cURL failed to init.');

    foreach (array(CURLOPT_URL => $url,
                   CURLOPT_CONNECTTIMEOUT => 10,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT => 'vitor@furlin.me')
    as $key => $value)
      if(!array_key_exists($key, $options))
        $options[$key] = $value;

    if(!curl_setopt_array($cURL_handle, $options))
      throw new Exception('cURL failed to set all options with curl_setopt_array.');

    if(!$content = curl_exec($cURL_handle))
      throw new Exception('cURL failed to execute.');

  } catch (Exception $e)
  {
    //rethrow the Exception
    throw new Exception($e);
  } finally
  {
    curl_close($cURL_handle);
    return $content ?? '';
  }
}
