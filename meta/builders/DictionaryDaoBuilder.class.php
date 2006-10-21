<?php
/***************************************************************************
 *   Copyright (C) 2006 by Konstantin V. Arkhipov                          *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
/* $Id$ */

	/**
	 * @ingroup Builders
	**/
	final class DictionaryDaoBuilder extends BaseBuilder
	{
		public static function build(MetaClass $class)
		{
			$out = self::getHead();
			
			if (sizeof($properties = $class->getProperties()) == 2) {
				if (isset($properties['id'], $properties['name'])) {
					$out .=
						"abstract class Auto{$class->getName()}DAO "
						."extends FinalNamedObjectDAO\n{\n";
					
					return
						$out
						.self::buildPointers($class)
						."\n}\n"
						.self::getHeel();
				}
			}
			
			$out .= <<<EOT
abstract class Auto{$class->getName()}DAO extends MappedStorableDAO
{
	protected \$mapping = array(

EOT;

			$mapping = self::buildMapping($class);
			$pointers = self::buildPointers($class);
			
			$out .= implode(",\n", $mapping);
			
			$className = $class->getName();
			$varName = strtolower($className[0]).substr($className, 1);
			
			$out .= <<<EOT

		);
		
{$pointers}

EOT;
			if ($class->getPattern() instanceof AbstractClassPattern) {
				$out .= <<<EOT

	public function setQueryFields(InsertOrUpdateQuery \$query, /* {$className} */ \${$varName})

EOT;
			} else {
				$out .= <<<EOT

	public function setQueryFields(InsertOrUpdateQuery \$query, {$className} \${$varName})

EOT;
			}
			
			$out .= <<<EOT
	{
		return
			\$query->

EOT;
			
			$out .= self::buildFillers($class);
			
			return $out.self::getHeel();
		}
	}
?>