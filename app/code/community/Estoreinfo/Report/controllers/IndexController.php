<?php
class Estoreinfo_Report_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $orders = array();   
        $resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$order_table = $resource->getTableName('sales/order');
		$creditmemo_table = $resource->getTableName('sales/creditmemo');
		
		/*get all records*/
        $orders['all']['total'] = Mage::getModel('sales/order')->getCollection()->getSize();		
		$orders['all']['cancelled'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CANCELED )->getSize();
		$orders['all']['refunded'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CLOSED)->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".Mage_Sales_Model_Order::STATE_COMPLETE."' LIMIT 1";
		$orders['all']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE 1 LIMIT 1";
		$orders['all']['returns'] = $readConnection->fetchOne($query);
		
		/*get 30 days records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-30 days'));
		$orders['30_days']['total'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		$orders['30_days']['cancelled'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CANCELED )->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();
		$orders['30_days']['refunded'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CLOSED)->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 30 DAY) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".Mage_Sales_Model_Order::STATE_COMPLETE."' LIMIT 1";
		$orders['30_days']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 30 DAY) LIMIT 1";
		$orders['30_days']['returns'] = $readConnection->fetchOne($query);
		
		/*get 90 days records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-90 days'));
		$orders['90_days']['total'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		$orders['90_days']['cancelled'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CANCELED )->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();
		$orders['90_days']['refunded'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CLOSED)->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 90 DAY) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".Mage_Sales_Model_Order::STATE_COMPLETE."' LIMIT 1";
		$orders['90_days']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 90 DAY) LIMIT 1";
		$orders['90_days']['returns'] = $readConnection->fetchOne($query);
		
		/*get 1 year records*/
		$fromDate = date('Y-m-d H:i:s', strtotime('-1 year'));
		$orders['1_year']['total'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		$orders['1_year']['cancelled'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CANCELED )->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();
		$orders['1_year']['refunded'] = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('state', Mage_Sales_Model_Order::STATE_CLOSED)->addFieldToFilter('created_at', array('from'=>$fromDate))->getSize();		
		
		$query = 'SELECT count(*) total_ontime FROM ' . $order_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 1 YEAR) AND HOUR(TIMEDIFF(created_at, updated_at)) < 48 AND state = '".Mage_Sales_Model_Order::STATE_COMPLETE."' LIMIT 1";
		$orders['1_year']['ontime_shipment'] = $readConnection->fetchOne($query);
		
		$query = 'SELECT count(*) total_return FROM ' . $creditmemo_table . " WHERE `created_at` >= DATE_SUB(Now(), INTERVAL 1 YEAR) LIMIT 1";
		$orders['1_year']['returns'] = $readConnection->fetchOne($query);
		
		$orders_json = json_encode($orders);
		echo $orders_json;
    }
}