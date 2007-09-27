<?php
/*****************************************************************************
 *   Copyright (C) 2006-2007, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-0.9.300 at 2007-05-15 14:27:41                       *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/
/* $Id$ */

	abstract class AutoStatisticClickDAO extends ComplexBuilderDAO
	{
		public function getTable()
		{
			return 'statistic_click';
		}
		
		public function getObjectName()
		{
			return 'StatisticClick';
		}
		
		public function getSequence()
		{
			return 'statistic_click_id';
		}
		
		/**
		 * @return InsertOrUpdateQuery
		**/
		public function setQueryFields(InsertOrUpdateQuery $query, StatisticClick $statisticClick)
		{
			return
				$query->
					set('id', $statisticClick->getId())->
					set('query_id', $statisticClick->getQuery()->getId())->
					set('when', $statisticClick->getWhen()->toString())->
					set('site', $statisticClick->getSite());
		}
		
		/**
		 * @return StatisticClick
		**/
		protected function makeSelf(&$array, $prefix = null)
		{
			$statisticClick = new StatisticClick();
			
			$statisticClick->
				setId($array[$prefix.'id'])->
				setWhen(new Timestamp($array[$prefix.'when']))->
				setSite($array[$prefix.'site']);
			
			return $statisticClick;
		}
		
		/**
		 * @return StatisticClick
		**/
		protected function makeCascade(/* StatisticClick */ $statisticClick, &$array, $prefix = null)
		{
			$statisticClick->
				setQuery(StatisticQuery::dao()->getById($array[$prefix.'query_id']));
			
			return $statisticClick;
		}
		
		/**
		 * @return StatisticClick
		**/
		protected function makeJoiners(/* StatisticClick */ $statisticClick, &$array, $prefix = null)
		{
			$statisticClick->
				setQuery(
					StatisticQuery::dao()->makeJoinedObject($array, StatisticQuery::dao()->getJoinPrefix('query_id', $prefix))
				);
			
			return $statisticClick;
		}
	}
?>