<?php

final class DataRepository 
{
    private $_connect_db;
    public function __construct(){
        $this->_connect_db = new Database;
    }

    public function payment_details(){
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `payment_details`");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }
    
    public function findAll() {
        $this->_connect_db->query(/** @lang text */"
            SELECT 
                users.id,
                users.name,
                users.email,
                users.role_id,
                users.telephone,
                roles.name AS role_name,
                users.created_at,
                users.updated_at
            FROM users
            INNER JOIN roles ON users.role_id = roles.id
        ");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }

    public function getInvoiceLastNumber() {
        $this->_connect_db->query(/** @lang text */'SELECT invoice_number FROM commercial_invoice_number ORDER BY invoice_number DESC LIMIT 1');
        $row = $this->_connect_db->single();
        return ($row) ? $row : false;
    }

    public function getAllProducts(){
        $this->_connect_db->query(/** @lang text */"SELECT `id`, `title`, `binding`, `sale_price`, `status` FROM `books` ");
        $row = $this->_connect_db->resultSet();
        if(!empty($row)){
            return $row;
        }else{
            return [];
        }
    }

    public function delete_product($ids) {
        if (!is_array($ids) || empty($ids)) return false;
        $placeholders = [];
        foreach ($ids as $index => $val) {
            $placeholders[] = ":id{$index}";
        }
        $inClause = implode(',', $placeholders);
        $sql = "DELETE FROM books WHERE id IN ($inClause)";
    
        $this->_connect_db->query($sql);
        foreach ($ids as $index => $val) {
            $this->_connect_db->bind(":id{$index}", $val);
        }
        return $this->_connect_db->execute();
    }
    
    public function add_product($product_title, $product_binding, $product_price){
        $this->_connect_db->query(/** @lang text */"INSERT INTO `books`(title, binding, sale_price) VALUES (:product_title, :product_binding, :product_price)");
        $this->_connect_db->bind(':product_title', $product_title);
        $this->_connect_db->bind(':product_binding', $product_binding);
        $this->_connect_db->bind(':product_price', $product_price);
        if($this->_connect_db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function edit_product($id){
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `books` WHERE id =:id ");
        $this->_connect_db->bind(':id', $id);
        $row = $this->_connect_db->single();
        if(!empty($row)){
            return $row;
        }else{
            return false;
        }
    }

    public function update_product($id, $product_title, $product_binding, $product_price){
        $this->_connect_db->query(/** @lang text */"UPDATE `books` SET title=:product_title, binding=:product_binding, sale_price=:product_price WHERE id=:id");
        $this->_connect_db->bind(':product_title', $product_title);
        $this->_connect_db->bind(':product_binding', $product_binding);
        $this->_connect_db->bind(':product_price', $product_price);
        $this->_connect_db->bind(':id', $id);
        if($this->_connect_db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function get_record(){
        $this->_connect_db->query(/** @lang text */"SELECT 
                users.id,
                users.name,
                users.email,
                users.role_id,
                roles.name AS role_name,
                users.created_at,
                users.updated_at
            FROM users
            INNER JOIN roles ON users.role_id = roles.id");
        $users = $this->_connect_db->resultSet();
        $user_count = $this->_connect_db->rowCount();

        $this->_connect_db->query(/** @lang text */"SELECT * FROM `books` ");
        $products_list = $this->_connect_db->resultSet();
        $product_list_count = $this->_connect_db->rowCount();

        $this->_connect_db->query(/** @lang text */"SELECT * FROM `invoice` ");
        $invoice_list = $this->_connect_db->resultSet();
        $invoice_list_count = $this->_connect_db->rowCount();
        
        $data = array (
            'users'=>[
                $users,
                $user_count
            ],
            'books'=>[
                $products_list,
                $product_list_count
            ],
            'invoices'=>[
                $invoice_list,
                $invoice_list_count
            ]
        );
        return $data;
    }

    public function processCompleteInvoice($invoiceData) {
        try {
            // Start transaction
            $this->_connect_db->beginTransaction();
            
            $invoice_number = $invoiceData['invoice']['invoice_number'];

            $invoice_number = str_replace('NGSB-', '', $invoice_number);
            
            if (!preg_match('/^\d+$/', $invoice_number)) {
                throw new Exception("Invalid invoice number format after processing");
            }
            
            $saved = $this->save_invoice_number($invoice_number);
            if (!$saved) {
                throw new Exception("Failed to save invoice number");
            }
            $client_address_array = ($invoiceData['invoice']['client_address']);
            $client_address_json = json_encode($client_address_array);
            // Save invoice header
            $invoiceId = $this->saveInvoiceHeader(
                $invoiceData['invoice']['invoice_number'],
                $invoiceData['invoice']['customer_id'],
                $invoiceData['invoice']['invoice_date'],
                $invoiceData['invoice']['shipping_via'],
                $invoiceData['invoice']['customer_reference'],
                $invoiceData['invoice']['client_name'],
                $invoiceData['invoice']['client_city'],
                $invoiceData['invoice']['client_telephone'], 
                $client_address_json, 
                $invoiceData['invoice']['invoice_type'],
                $invoiceData['invoice']['terms'],
                $invoiceData['invoice']['total_amount'],
                $invoiceData['invoice']['paymentMethod'],
                $invoiceData['invoice']['deliveryCost']
            );
            
            
            if (!$invoiceId) {
                throw new Exception("Failed to save invoice header");
            }

            // Process each section
            foreach ($invoiceData['sections'] as $section) {
                $sectionLabel = key($section);
                $sectionData = $section[$sectionLabel][0];
                
                // Save section
                $sectionId = $this->saveInvoiceSection(
                    $invoiceId,
                    $sectionLabel,
                    $sectionData['discount_percent'],
                    $sectionData['sub_total'],
                    $sectionData['discount_amount'],
                    $sectionData['total_after_discount']
                );
    
                if (!$sectionId) {
                    throw new Exception("Failed to save invoice section $sectionLabel");
                }
    
                // Save items for this section
                foreach ($sectionData['items'] as $item) {
                    if (!$this->saveInvoiceItem(
                        $sectionId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['unit_price'],
                        $item['total']
                    )) {
                        throw new Exception("Failed to save item for product {$item['product_id']} in section $sectionLabel");
                    }
                }
            }
    
            // Commit transaction if all operations succeeded
            $this->_connect_db->commit();
    
            return [
                'status' => 'success',
                'invoice_id' => $invoiceId
            ];
    
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->_connect_db->rollBack();
            
            return [
                'status' => 'error',
                'message' => 'Invoice processing failed',
                'details' => $e->getMessage()
            ];
        }
    }
    
    private function saveInvoiceHeader($invoiceNumber, $customerId, $invoiceDate, $shippingVia, 
                                    $customerReference, $clientName, $clientCity, $client_telephone,
                                    $client_address, $invoiceType, $terms, $totalAmount, $paymentMethod, $deliveryCost) {
        
        $this->_connect_db->query("INSERT INTO invoice (invoice_number, customer_id, invoice_date, shipping_via, 
                customer_reference, client_name, client_city, client_address, client_telephone, invoice_type, terms, total_amount, delivery_cost, payment_method_id, `createdAt`)
                VALUES (:invoiceNumber, :customerId, :invoiceDate, :shippingVia, :customerReference, 
                :clientName, :clientCity, :client_address,  :client_telephone, :invoiceType, :terms, :totalAmount, :deliveryCost, :paymentMethod, NOW())");
        
        $this->_connect_db->bind(':invoiceNumber', $invoiceNumber);
        $this->_connect_db->bind(':customerId', $customerId);
        $this->_connect_db->bind(':invoiceDate', $invoiceDate);
        $this->_connect_db->bind(':shippingVia', $shippingVia);
        $this->_connect_db->bind(':customerReference', $customerReference);
        $this->_connect_db->bind(':clientName', $clientName);
        $this->_connect_db->bind(':clientCity', $clientCity);
        $this->_connect_db->bind(':client_address', $client_address);
        $this->_connect_db->bind(':client_telephone', $client_telephone);
        $this->_connect_db->bind(':invoiceType', $invoiceType);
        $this->_connect_db->bind(':terms', $terms);
        $this->_connect_db->bind(':totalAmount', $totalAmount);
        $this->_connect_db->bind(':paymentMethod', $paymentMethod); 
        $this->_connect_db->bind(':deliveryCost', $deliveryCost);
        return $this->_connect_db->execute() ? $this->_connect_db->lastInsertId() : false;
    }
    
    private function saveInvoiceSection($invoiceId, $label, $discountPercent, $subTotal, 
                                     $discountAmount, $totalAfterDiscount) {
        $this->_connect_db->query("INSERT INTO invoice_section (invoice_id, label, discount_percent, 
                sub_total, discount_amount, total_after_discount)
                VALUES (:invoiceId, :label, :discountPercent, :subTotal, :discountAmount, :totalAfterDiscount)");
        
        $this->_connect_db->bind(':invoiceId', $invoiceId);
        $this->_connect_db->bind(':label', $label);
        $this->_connect_db->bind(':discountPercent', $discountPercent);
        $this->_connect_db->bind(':subTotal', $subTotal);
        $this->_connect_db->bind(':discountAmount', $discountAmount);
        $this->_connect_db->bind(':totalAfterDiscount', $totalAfterDiscount);
        
        return $this->_connect_db->execute() ? $this->_connect_db->lastInsertId() : false;
    }
    
    private function saveInvoiceItem($sectionId, $productId, $quantity, $unitPrice, $total) {
        $this->_connect_db->query("INSERT INTO invoice_items (section_id, product_id, quantity, 
                unit_price, total) VALUES (:sectionId, :productId, :quantity, :unitPrice, :total)");
        
        $this->_connect_db->bind(':sectionId', $sectionId);
        $this->_connect_db->bind(':productId', $productId);
        $this->_connect_db->bind(':quantity', $quantity);
        $this->_connect_db->bind(':unitPrice', $unitPrice);
        $this->_connect_db->bind(':total', $total);
        
        return $this->_connect_db->execute();
    }

    public function save_invoice_number($invoice){
        $this->_connect_db->query("INSERT INTO `commercial_invoice_number` (invoice_number) VALUES (:invoice)");
        $this->_connect_db->bind(':invoice', $invoice);
        if($this->_connect_db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function getAllInvoice(){
        // Fetch all invoices
        $this->_connect_db->query("SELECT i.*, u.name, u.id, i.id AS invoice_id FROM invoice i INNER JOIN users u ON i.customer_id=u.id  ORDER BY i.createdAt DESC");
        $invoices = $this->_connect_db->fetchAll();
    
        // Fetch all sections
        $this->_connect_db->query("SELECT * FROM invoice_section");
        $sections = $this->_connect_db->fetchAll();
    
        // Fetch all items
        $this->_connect_db->query("SELECT * FROM invoice_items");
        $items = $this->_connect_db->fetchAll();
    
        // Map items to their sections
        $sectionItems = [];
        foreach ($items as $item) {
            $sectionItems[$item['section_id']][] = $item;
        }
    
        // Map sections to their invoices with nested items
        $invoiceSections = [];
        foreach ($sections as $section) {
            $section['items'] = $sectionItems[$section['id']] ?? [];
            $invoiceSections[$section['invoice_id']][] = $section;
        }
    
        // Add sections to invoices
        foreach ($invoices as &$invoice) {
            $invoice['sections'] = $invoiceSections[$invoice['id']] ?? [];
        }
    
        return $invoices;
    }

    public function delete_invoice($ids) {
        if (!is_array($ids) || empty($ids)) {
            return false;
        }
    
        try {
            $this->_connect_db->beginTransaction();
    
            $placeholders = [];
            foreach ($ids as $index => $val) {
                $placeholders[] = ":id{$index}";
            }
            $inClause = implode(',', $placeholders);
            
            $sql = "DELETE invoice_items 
                    FROM invoice_items
                    JOIN invoice_section ON invoice_items.section_id = invoice_section.id
                    WHERE invoice_section.invoice_id IN ($inClause)";
            
            $this->_connect_db->query($sql);
            foreach ($ids as $index => $val) {
                $this->_connect_db->bind(":id{$index}", $val);
            }
            $this->_connect_db->execute();
    
            $sql = "DELETE FROM invoice_section WHERE invoice_id IN ($inClause)";
            $this->_connect_db->query($sql);
            foreach ($ids as $index => $val) {
                $this->_connect_db->bind(":id{$index}", $val);
            }
            $this->_connect_db->execute();
    
            $sql = "DELETE FROM invoice WHERE id IN ($inClause)";
            $this->_connect_db->query($sql);
            foreach ($ids as $index => $val) {
                $this->_connect_db->bind(":id{$index}", $val);
            }
            $result = $this->_connect_db->execute();
    
            $this->_connect_db->commit();
            return $result;
        } catch (Exception $e) {
            $this->_connect_db->rollBack();
            error_log("Error deleting invoice: " . $e->getMessage());
            return false;
        }
    }

    public function getInvoiceById($id) {
        try {
            // Get invoice header
            $this->_connect_db->query("
                SELECT i.*, u.name, u.telephone, u.role_id as role, u.id as customer_id, r.id, r.name as role_name 
                FROM invoice i INNER JOIN users u ON u.id = i.customer_id INNER JOIN roles r ON u.role_id = r.id
                WHERE i.id = :id");
            $this->_connect_db->bind(':id', $id);
            $invoice = $this->_connect_db->single();
            if (!$invoice) {
                return null;
            }
            
            // Get payment method if exists
            $paymentMethod = null;
            if (!empty($invoice->payment_method_id)) {
                $this->_connect_db->query("
                    SELECT * FROM payment_details 
                    WHERE id = :payment_method_id
                ");
                $this->_connect_db->bind(':payment_method_id', $invoice->payment_method_id);
                $paymentMethod = $this->_connect_db->single();
            }
    
            // Get sections
            $this->_connect_db->query("
                SELECT * FROM invoice_section 
                WHERE invoice_id = :invoice_id
                ORDER BY id
            ");
            $this->_connect_db->bind(':invoice_id', $id);
            $sections = $this->_connect_db->resultset();
            
            $formattedSections = [];
            
            foreach ($sections as $section) {
                // Convert section to array if it's an object
                if (is_object($section)) {
                    $section = (array)$section;
                }
                
                $label = $section['label'];
                
                // Get items for this section
                $this->_connect_db->query("
                    SELECT ii.*, b.title, b.binding, b.sale_price 
                    FROM invoice_items ii
                    JOIN books b ON ii.product_id = b.id
                    WHERE ii.section_id = :section_id
                ");
                $this->_connect_db->bind(':section_id', $section['id']);
                $items = $this->_connect_db->resultset();
                
                // Format items with full product details
                $formattedItems = [];
                foreach ($items as $item) {
                    // Convert item to array if it's an object
                    if (is_object($item)) {
                        $item = (array)$item;
                    }
                    
                    $formattedItems[] = [
                        'product' => [
                            'id' => $item['product_id'],
                            'title' => $item['title'],
                            'binding' => $item['binding'],
                            'sale_price' => $item['sale_price']
                        ],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total' => $item['total']
                    ];
                }
                
                // Format section data
                $formattedSections[] = [
                    $label => [
                        [
                            'discount_percent' => $section['discount_percent'],
                            'discount_amount' => $section['discount_amount'],
                            'sub_total' => $section['sub_total'],
                            'total_after_discount' => $section['total_after_discount'],
                            'items' => $formattedItems
                        ]
                    ]
                ];
            }
            // Build final response 
            return [
                'invoice' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_id' => $invoice->customer_id,
                    'invoice_date' => $invoice->invoice_date,
                    'shipping_via' => $invoice->shipping_via,
                    'customer_reference' => $invoice->customer_reference,
                    'client_name' => $invoice->client_name,
                    'client_city' => $invoice->client_city,
                    'client_address' =>json_decode($invoice->client_address, true),
                    'client_telephone' => $invoice->client_telephone,
                    'invoice_type' => $invoice->invoice_type,
                    'terms' => $invoice->terms,
                    'total_amount' => $invoice->total_amount,
                    'paymentMethod' => $paymentMethod,
                    'officer'=>$invoice->name,
                    'office_telephone'=>$invoice->telephone,
                    'office_role_id'=>$invoice->role,
                    'office_role'=>$invoice->role_name,
                    'office_id'=>$invoice->customer_id,
                    'delivery_cost'=>$invoice->delivery_cost,

                ],
                'sections' => $formattedSections
            ];
            
        } catch (Exception $e) {
            error_log("Error getting invoice: " . $e->getMessage());
            return null;
        }
    }

    public function getUserById($id){
        $this->_connect_db->query(/** @lang text */"
                SELECT u.email, u.name, u.telephone, u.role_id as role, u.id as customer_id, r.id, r.name as role_name 
            FROM users u INNER JOIN roles r ON u.role_id = r.id
            WHERE u.id = :id");
        $this->_connect_db->bind(':id', $id);
        $user = $this->_connect_db->single();
        if (!empty($user)){
            return $user;
        }else{
            return null;
        }
    }

    public function getUserRoles(){
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `roles`");
        $roles = $this->_connect_db->fetchAll();
        return $roles;
    }

    public function findCountRecords(){
        $data = array();
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `records`");
        $row = $this->_connect_db->fetchAll();

        foreach ($row as $k) {
            $data['printed_invoices']= (!empty($k)?$k['no_printed_invoices']:'0');
            $data['printed_users']= (!empty($k)?$k['no_printed_users']:'0');
            $data['printed_books']= (!empty($k)?$k['no_printed_books']:'0');
            $data['send_emails']= (!empty($k)?$k['no_send_emails']:'0');
            $data['send_invoices']= (!empty($k)?$k['no_send_invoices']:'0');
        }
     
        $this->_connect_db->query(/** @lang text */"SELECT * FROM `users`");
        $users = $this->_connect_db->rowCount();
        $data['users']= (!empty($users)?$users:'0');

        $this->_connect_db->query(/** @lang text */"SELECT * FROM `books`");
        $books = $this->_connect_db->rowCount();
        $data['books']= (!empty($books)?$books:'0');

        $this->_connect_db->query(/** @lang text */"SELECT * FROM `invoice`");
        $invoice = $this->_connect_db->rowCount();
        $data['invoices']= (!empty($invoice)?$invoice:'0');


        $this->_connect_db->query(/** @lang text */"SELECT * FROM `roles`");
        $roles = $this->_connect_db->rowCount();
        $data['roles']= (!empty($roles)?$roles:'0');

        $this->_connect_db->query(/** @lang text */"SELECT * FROM `payment_details`");
        $payment_method = $this->_connect_db->rowCount();
        $data['payment_method']= (!empty($payment_method)?$payment_method:'0');

        return $data;
    }

    public function save_settings($company_name, $company_tag, $company_rc_number, $company_email, $company_address, $company_url, $company_country, $company_city, $company_telephone, $mainLogoDbPath, $iconLogoDbPath){
        $this->_connect_db->query("INSERT INTO `settings`(`company_name`, `company_tagline`, `company_logo`, `company_icon_logo`, `company_rc`, `company_email`, `company_address`, `company_telephone`, `company_website`, `company_country`, `company_city`)VALUES (:company_name, :company_tag, :mainLogoDbPath, :iconLogoDbPath, :company_rc_number, :company_email, :company_address, :company_telephone, :company_url, :company_country, :company_city)");
        $this->_connect_db->bind(':company_name', $company_name);
        $this->_connect_db->bind(':company_tag', $company_tag);
        $this->_connect_db->bind(':company_rc_number', $company_rc_number);
        $this->_connect_db->bind(':company_email', $company_email);
        $this->_connect_db->bind(':company_address', $company_address);
        $this->_connect_db->bind(':company_url', $company_url);
        $this->_connect_db->bind(':company_country', $company_country);
        $this->_connect_db->bind(':company_city', $company_city);
        $this->_connect_db->bind(':company_telephone', $company_telephone);
        $this->_connect_db->bind(':mainLogoDbPath', $mainLogoDbPath);
        $this->_connect_db->bind(':iconLogoDbPath', $iconLogoDbPath);

        return $this->_connect_db->execute();
    }

    public function get_app_settings(){
        $this->_connect_db->query("SELECT * FROM settings");
        $sections = $this->_connect_db->single();
        return(!empty($sections)? $sections : null);
    }

    public function processUpdateInvoice($invoiceData, $invoiceId) {
        try {
            $this->_connect_db->beginTransaction();
    
            $invoice_number = $invoiceData['invoice']['invoice_number'];
            $invoice_number = str_replace('NGSB-', '', $invoice_number);
    
            if (!preg_match('/^\d+$/', $invoice_number)) {
                throw new Exception("Invalid invoice number format after processing");
            }
    
            // update invoice number record if you want to track it
            $this->update_invoice_number($invoice_number); 
    
            $client_address_array = ($invoiceData['invoice']['client_address']);
            $client_address_json = json_encode($client_address_array);
            // Update invoice header
            $updated = $this->updateInvoiceHeader(
                $invoiceId,
                $invoiceData['invoice']['invoice_number'],
                $invoiceData['invoice']['customer_id'],
                $invoiceData['invoice']['invoice_date'],
                $invoiceData['invoice']['shipping_via'],
                $invoiceData['invoice']['customer_reference'],
                $invoiceData['invoice']['client_name'],
                $invoiceData['invoice']['client_city'],
                $invoiceData['invoice']['client_telephone'], 
                $client_address_json, 
                $invoiceData['invoice']['invoice_type'],
                $invoiceData['invoice']['terms'],
                $invoiceData['invoice']['total_amount'],
                $invoiceData['invoice']['paymentMethod'],
                $invoiceData['invoice']['deliveryCost']
            );
            
            if (!$updated) {
                throw new Exception("Failed to update invoice header");
            }
    
            // Delete previous sections and items
            $this->deleteInvoiceSectionsAndItems($invoiceId);
    
            // Save new sections and items
            foreach ($invoiceData['sections'] as $section) {
                $sectionLabel = key($section);
                $sectionData = $section[$sectionLabel][0];
    
                $sectionId = $this->saveInvoiceSection(
                    $invoiceId,
                    $sectionLabel,
                    $sectionData['discount_percent'],
                    $sectionData['sub_total'],
                    $sectionData['discount_amount'],
                    $sectionData['total_after_discount']
                );
    
                if (!$sectionId) {
                    throw new Exception("Failed to save section $sectionLabel");
                }
    
                foreach ($sectionData['items'] as $item) {
                    if (!$this->saveInvoiceItem(
                        $sectionId,
                        $item['product_id'],
                        $item['quantity'],
                        $item['unit_price'],
                        $item['total']
                    )) {
                        throw new Exception("Failed to save item for product {$item['product_id']}");
                    }
                }
            }
    
            $this->_connect_db->commit();
    
            return [
                'status' => 'success',
                'invoice_id' => $invoiceId
            ];
    
        } catch (Exception $e) {
            $this->_connect_db->rollBack();
    
            return [
                'status' => 'error',
                'message' => 'Invoice update failed',
                'details' => $e->getMessage()
            ];
        }
    }
    
    private function updateInvoiceHeader($id, $invoiceNumber, $customerId, $invoiceDate, $shippingVia, $customerReference, $clientName, $clientCity, $client_telephone, $client_address, $invoiceType, $terms, $totalAmount, $paymentMethod, $deliveryCost) {
        $this->_connect_db->query("UPDATE invoice SET
            invoice_number = :invoiceNumber,
            customer_id = :customerId,
            invoice_date = :invoiceDate,
            shipping_via = :shippingVia,
            customer_reference = :customerReference,
            client_name = :clientName,
            client_city = :clientCity,
            client_telephone = :client_telephone,
            client_address =:client_address,
            invoice_type = :invoiceType,
            terms = :terms,
            total_amount = :totalAmount,
            delivery_cost =:deliveryCost,
            payment_method_id = :paymentMethod,
            updatedAt = NOW()
            WHERE id = :id");

        $this->_connect_db->bind(':invoiceNumber', $invoiceNumber);
        $this->_connect_db->bind(':customerId', $customerId);
        $this->_connect_db->bind(':invoiceDate', $invoiceDate);
        $this->_connect_db->bind(':shippingVia', $shippingVia);
        $this->_connect_db->bind(':customerReference', $customerReference);
        $this->_connect_db->bind(':clientName', $clientName);
        $this->_connect_db->bind(':clientCity', $clientCity);
        $this->_connect_db->bind(':client_telephone', $client_telephone);
        $this->_connect_db->bind(':client_address', $client_address);
        $this->_connect_db->bind(':invoiceType', $invoiceType);
        $this->_connect_db->bind(':terms', $terms);
        $this->_connect_db->bind(':totalAmount', $totalAmount);
        $this->_connect_db->bind(':deliveryCost', $deliveryCost);
        $this->_connect_db->bind(':paymentMethod', $paymentMethod);
        $this->_connect_db->bind(':id', $id);

        return $this->_connect_db->execute();
    }

    private function deleteInvoiceSectionsAndItems($invoiceId) {
        // Delete invoice items first
        $this->_connect_db->query("DELETE i FROM invoice_items i
            INNER JOIN invoice_section s ON i.section_id = s.id
            WHERE s.invoice_id = :invoiceId");
        $this->_connect_db->bind(':invoiceId', $invoiceId);
        $this->_connect_db->execute();
    
        // Then delete invoice sections
        $this->_connect_db->query("DELETE FROM invoice_section WHERE invoice_id = :invoiceId");
        $this->_connect_db->bind(':invoiceId', $invoiceId);
        $this->_connect_db->execute();
    }

    public function update_invoice_number($invoice) {
        $this->_connect_db->query("UPDATE commercial_invoice_number SET invoice_number = :invoice WHERE invoice_number = :invoice");
        $this->_connect_db->bind(':invoice', $invoice);
        return $this->_connect_db->execute();
    }
    
    
}