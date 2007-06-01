<?php
/***************************************************************************
 *   Copyright (C) 2007 by Igor V. Gulyaev                                 *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */


	class DateRangeDisplayFilter extends BaseFilter
	{
		public static function me()
		{
			return Singleton::getInstance('DateRangeDisplayFilter');
		}
		
		public function apply($value)
		{
			$result = null;
			
			if ($value instanceof DateRange) {
				if ($value->getStart())
					$result = $value->getStart()->toDate('.');
					
				$result .= ' - ';
				
				if ($value->getEnd())
					$result .= $value->getEnd()->toDate('.');
				
				return $result;
			} else {
				return $value;
			}
		}
	}
?>