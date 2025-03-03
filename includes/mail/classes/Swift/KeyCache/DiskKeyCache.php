<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//@require 'Swift/KeyCache.php';
//@require 'Swift/KeyCacheInputStream.php';
//@require 'Swift/InputByteStream.php';
//@require 'Swift/OutputByteStrean.php';
//@require 'Swift/SwiftException.php';
//@require 'Swift/IoException.php';

/**
 * A KeyCache which streams to and from disk.
 * @package Swift
 * @subpackage KeyCache
 * @author Chris Corbyn
 */
class Swift_KeyCache_DiskKeyCache implements Swift_KeyCache
{

  /** Signal to place pointer at start of file */
  const POSITION_START = 0;

  /** Signal to place pointer at end of file */
  const POSITION_END = 1;

  /**
   * An InputStream for cloning.
   * @var Swift_KeyCache_KeyCacheInputStream
   * @access private
   */
  private $_stream;

  /**
   * A path to write to.
   * @var string
   * @access private
   */
  private $_path;

  /**
   * Stored keys.
   * @var array
   * @access private
   */
  private $_keys = array();

  /**
   * Will be true if magic_quotes_runtime is turned on.
   * @var boolean
   * @access private
   */
  private $_quotes = false;

  /**
   * Create a new DiskKeyCache with the given $stream for cloning to make
   * InputByteStreams, and the given $path to save to.
   * @param Swift_KeyCache_KeyCacheInputStream $stream
   * @param string $path to save to
   */
  public function __construct(Swift_KeyCache_KeyCacheInputStream $stream, $path)
  {
    $this->_stream = $stream;
    $this->_path = $path;
    $this->_quotes = @get_magic_quotes_runtime();
  }

  /**
   * Set a string into the cache under $itemKey for the namespace $nsKey.
   * @param string $nsKey
   * @param string $itemKey
   * @param string $string
   * @param int $mode
   * @throws Swift_IoException
   * @see MODE_WRITE, MODE_APPEND
   */
  public function setString($nsKey, $itemKey, $string, $mode)
  {
    $this->_prepareCache($nsKey);
    switch ($mode)
    {
      case self::MODE_WRITE:
        $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_START);
        break;
      case self::MODE_APPEND:
        $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_END);
        break;
      default:
        throw new Swift_SwiftException(
          'Invalid mode [' . $mode . '] used to set nsKey='.
          $nsKey . ', itemKey=' . $itemKey
          );
        break;
    }
    fwrite($fp, $string);
  }

  /**
   * Set a ByteStream into the cache under $itemKey for the namespace $nsKey.
   * @param string $nsKey
   * @param string $itemKey
   * @param Swift_OutputByteStream $os
   * @param int $mode
   * @see MODE_WRITE, MODE_APPEND
   * @throws Swift_IoException
   */
  public function importFromByteStream($nsKey, $itemKey, Swift_OutputByteStream $os,
    $mode)
  {
    $this->_prepareCache($nsKey);
    switch ($mode)
    {
      case self::MODE_WRITE:
        $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_START);
        break;
      case self::MODE_APPEND:
        $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_END);
        break;
      default:
        throw new Swift_SwiftException(
          'Invalid mode [' . $mode . '] used to set nsKey='.
          $nsKey . ', itemKey=' . $itemKey
          );
        break;
    }
    while (false !== $bytes = $os->read(8192))
    {
      fwrite($fp, $bytes);
    }
  }

  /**
   * Provides a ByteStream which when written to, writes data to $itemKey.
   * NOTE: The stream will always write in append mode.
   * @param string $nsKey
   * @param string $itemKey
   * @return Swift_InputByteStream
   */
  public function getInputByteStream($nsKey, $itemKey,
    Swift_InputByteStream $writeThrough = null)
  {
    $is = clone $this->_stream;
    $is->setKeyCache($this);
    $is->setNsKey($nsKey);
    $is->setItemKey($itemKey);
    if (isset($writeThrough))
    {
      $is->setWriteThroughStream($writeThrough);
    }
    return $is;
  }

  /**
   * Get data back out of the cache as a string.
   * @param string $nsKey
   * @param string $itemKey
   * @return string
   * @throws Swift_IoException
   */
  public function getString($nsKey, $itemKey)
  {
    $this->_prepareCache($nsKey);
    if ($this->hasKey($nsKey, $itemKey))
    {
      $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_START);
      if ($this->_quotes)
      {
        set_magic_quotes_runtime(0);
      }
      $str = '';
      while (!feof($fp) && false !== $bytes = fread($fp, 8192))
      {
        $str .= $bytes;
      }
      if ($this->_quotes)
      {
        set_magic_quotes_runtime(1);
      }
      return $str;
    }
  }

  /**
   * Get data back out of the cache as a ByteStream.
   * @param string $nsKey
   * @param string $itemKey
   * @param Swift_InputByteStream $is to write the data to
   */
  public function exportToByteStream($nsKey, $itemKey, Swift_InputByteStream $is)
  {
    if ($this->hasKey($nsKey, $itemKey))
    {
      $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_START);
      if ($this->_quotes)
      {
        set_magic_quotes_runtime(0);
      }
      while (!feof($fp) && false !== $bytes = fread($fp, 8192))
      {
        $is->write($bytes);
      }
      if ($this->_quotes)
      {
        set_magic_quotes_runtime(1);
      }
    }
  }

  /**
   * Check if the given $itemKey exists in the namespace $nsKey.
   * @param string $nsKey
   * @param string $itemKey
   * @return boolean
   */
  public function hasKey($nsKey, $itemKey)
  {
    return is_file($this->_path . '/' . $nsKey . '/' . $itemKey);
  }

  /**
   * Clear data for $itemKey in the namespace $nsKey if it exists.
   * @param string $nsKey
   * @param string $itemKey
   */
  public function clearKey($nsKey, $itemKey)
  {
    if ($this->hasKey($nsKey, $itemKey))
    {
      $fp = $this->_getHandle($nsKey, $itemKey, self::POSITION_END);
      fclose($fp);
      unlink($this->_path . '/' . $nsKey . '/' . $itemKey);
    }
    unset($this->_keys[$nsKey][$itemKey]);
  }

  /**
   * Clear all data in the namespace $nsKey if it exists.
   * @param string $nsKey
   */
  public function clearAll($nsKey)
  {
    if (array_key_exists($nsKey, $this->_keys))
    {
      foreach ($this->_keys[$nsKey] as $itemKey=>$null)
      {
        $this->clearKey($nsKey, $itemKey);
      }
      rmdir($this->_path . '/' . $nsKey);
      unset($this->_keys[$nsKey]);
    }
  }

  // -- Private methods

  /**
   * Initialize the namespace of $nsKey if needed.
   * @param string $nsKey
   * @access private
   */
  private function _prepareCache($nsKey)
  {
    $cacheDir = $this->_path . '/' . $nsKey;
    if (!is_dir($cacheDir))
    {
      if (!mkdir($cacheDir))
      {
        throw new Swift_IoException('Failed to create cache directory ' . $cacheDir);
      }
      $this->_keys[$nsKey] = array();
    }
  }

  /**
   * Get a file handle on the cache item.
   * @param string $nsKey
   * @param string $itemKey
   * @param int $position
   * @return resource
   * @access private
   */
  private function _getHandle($nsKey, $itemKey, $position)
  {
    if (!isset($this->_keys[$nsKey]) || !array_key_exists($itemKey, $this->_keys[$nsKey]))
    {
      $fp = fopen($this->_path . '/' . $nsKey . '/' . $itemKey, 'w+b');
      $this->_keys[$nsKey][$itemKey] = $fp;
    }
    if (self::POSITION_START == $position)
    {
      fseek($this->_keys[$nsKey][$itemKey], 0, SEEK_SET);
    }
    else
    {
      fseek($this->_keys[$nsKey][$itemKey], 0, SEEK_END);
    }
    return $this->_keys[$nsKey][$itemKey];
  }

  /**
   * Destructor.
   */
  public function __destruct()
  {
    foreach ($this->_keys as $nsKey=>$null)
    {
      $this->clearAll($nsKey);
    }
  }

}
