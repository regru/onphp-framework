<?php
/***************************************************************************
 *   Copyright (C) 2007 by Anton E. Lebedevich                             *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	class FileRandomSource implements RandomSource
	{
		private $handle = null;
		
		public function __construct($filename)
		{
			Assert::isTrue(file_exists($filename) && is_readable($filename));
			
			$this->handle = fopen($filename, 'r');
		}
		
		public function __destruct()
		{
			fclose($this->handle);
		}
		
		public function getBytes($numberOfBytes)
		{
			Assert::isPositiveInteger($numberOfBytes);
			
			return fread($this->handle, $numberOfBytes);
		}
	}
?>