<?php
/**
 * Created by Ruben Podadera. e-mail: ruben.podadera@gmail.com
 * Date: 2/12/14
 * Time: 12:02 PM
 * Credit Transfert Transactions
 */

namespace SEPA;

/**
 * Class SepaDirectDebitTransaction
 * @package SEPA
 */
class CreditTransferTransaction extends PaymentInfo implements TransactionInterface {
	const DEFAULT_CURRENCY = 'EUR';

    /**
     * Unique identification as assigned by an instructing party for an instructed party to unambiguously identify
     * the instruction.
     * @var string
     */
    private $InstructionIdentification = '';

    /**
     *Unique identification assigned by the initiating party to unumbiguously identify the transaction.
     * This identification is passed on, unchanged, throughout the entire end-to-end chain.
     * @var string
     */
    private $EndToEndIdentification = '';


    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     * @var float
     */
    private $InstructedAmount = 0.00;

    /**
     * Credit Bank BIC
     * @var string
     */
    private $BIC = '';

    /**
     * Credit IBAN
     * @var string
     */
    private $IBAN = '';


    /**
     * Information supplied to enable the matching/reconciliation of an entry with the items that the payment is
     * intended to settle, such as commercial invoices in an accounts' receivable system, in an unstructured form.
     * max 140 length
     * @var string
     */
    private $creditInvoice = '';

    private $creditInvoiceCode = '';

    private $creditInvoiceReference = '';


    /**
     * Creditor Name
     * @var string
     */
    private $creditorName = '';

    //Postal Address
    private $creditorAddressLine = '';
    private $creditorCountry = '';


    private $currency = '';

    /**
     * @param $instructionIdentifier
     * @return $this
     */
    public function setInstructionIdentification($instructionIdentifier) {
        $this->InstructionIdentification = $instructionIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionIdentification() {
        return $this->InstructionIdentification;
    }

    /**
     * @param $instructionIdentifierEndToEnd
     * @return $this
     */
    public function setEndToEndIdentification($instructionIdentifierEndToEnd) {
        $this->EndToEndIdentification = $instructionIdentifierEndToEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndToEndIdentification() {
        return $this->EndToEndIdentification;
    }

    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     * @param $amount
     * @return $this
     */
    public function setInstructedAmount($amount) {
        $this->InstructedAmount = $this->amountToString($amount);
        return $this;
    }

    public function getInstructedAmount() {
        return $this->InstructedAmount;
    }

    /**
     * @return string
     */
    public function getBIC() {
        return $this->BIC;
    }

    /**
     * Financial institution servicing an account for the creditor.
     * Bank Identifier Code.
     * max length
     * @param $BIC
     * @return $this
     */
    public function setBIC($BIC) {

        $this->BIC = $this->removeSpaces($BIC);

        return $this;
    }

    /**
     * @return string
     */
    public function getIBAN() {

        return $this->IBAN;
    }

    /**
     * Credit IBAN
     * max  34 length
     * @param $IBAN
     * @throws \Exception
     * @return $this
     */
    public function setIBAN($IBAN) {
        $IBAN = $this->removeSpaces($IBAN);

        if ( !$this->checkIBAN($IBAN) ) {

            throw new \Exception(ERROR_MSG_DD_IBAN . $this->getInstructionIdentification());
        }
        $this->IBAN = $IBAN;
        return $this;
    }


    /**
     * @return string
     */
    public function getCreditInvoice() {
        return $this->creditInvoice;
    }

    public function getCreditInvoiceCode() {
        return $this->creditInvoiceCode;
    }

    public function getCreditInvoiceReference() {
        return $this->creditInvoiceReference;
    }


    /**
     * Credit Invoice
     * @param $invoice
     * @return $this
     * @throws \Exception
     */
    public function setCreditInvoice($invoice) {
        $invoice = $this->unicodeDecode($invoice);

        if ( !$this->checkStringLength($invoice, 140) ) {

            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->creditInvoice = $invoice;
        return $this;
    }

    public function setCreditInvoiceCode($invoice) {
        $invoice = $this->unicodeDecode($invoice);

        if ( !$this->checkStringLength($invoice, 140) ) {

            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->creditInvoiceCode = $invoice;
        return $this;
    }

    public function setCreditInvoiceReference($invoice) {
        $invoice = $this->unicodeDecode($invoice);

        if ( !$this->checkStringLength($invoice, 140) ) {

            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->creditInvoiceReference = $invoice;
        return $this;
    }


    /**
     * @return string
     */
    public function getCreditorName() {
        return $this->creditorName;
    }

    public function getCreditorAddressLine() {
        return $this->creditorAddressLine;
    }

    public function getCreditorCountry() {
        return $this->creditorCountry;
    }

    /**
     * Name by which a party is known and which is usually used to identify that party.
     * @param $name
     * @return $this
     * @throws \Exception
     */
    public function setCreditorName($name) {
        $name = $this->unicodeDecode($name);

        if ( !$this->checkStringLength($name, 70) ) {

            throw new \Exception(ERROR_MSG_DD_NAME . $this->getInstructionIdentification());
        }
        $this->creditorName = $name;
        return $this;
    }

    public function setCreditorAddressLine($name) {
        $name = $this->unicodeDecode($name);

        if ( !$this->checkStringLength($name, 140)) {

            throw new \Exception(ERROR_MSG_INITIATING_PARTY_NAME);
        }

        $this->creditorAddressLine = $name;

        return $this;
    }

    public function setCreditorCountry($name) {
        $name = $this->unicodeDecode($name);

        if ( !$this->checkStringLength($name, 140)) {

            throw new \Exception(ERROR_MSG_INITIATING_PARTY_NAME);
        }

        $this->creditorCountry = $name;

        return $this;
    }

    public function setCurrency($currency) {
        $this->currency = strtoupper($currency);
        return $this;
    }

    public function getCurrency() {
        if ( empty($this->currency) || is_null($this->currency) ) {

            $this->currency = self::DEFAULT_CURRENCY;
        }
        return $this->currency;
    }



    public function checkIsValidTransaction()
    {
        if ( !$this->getBIC() ||  !$this->getIBAN() || !$this->getCreditorName()) {
           return false;
        }
        return true;
    }

    public function getSimpleXMLElementTransaction() {
        $creditTransferTransactionInformation = new \SimpleXMLElement('<CdtTrfTxInf></CdtTrfTxInf>');

        $paymentIdentification = $creditTransferTransactionInformation->addChild('PmtId');
        $paymentIdentification->addChild('InstrId', $this->getInstructionIdentification());
        $paymentIdentification->addChild('EndToEndId', $this->getEndToEndIdentification());

        $amount = $creditTransferTransactionInformation->addChild('Amt');
        $amount->addChild('InstdAmt', $this->getInstructedAmount())
            ->addAttribute('Ccy', $this->getCurrency());

        $creditorAgent  = $creditTransferTransactionInformation->addChild('CdtrAgt');
        $financialInstitution = $creditorAgent->addChild('FinInstnId');
        $financialInstitution->addChild('BIC', $this->getBIC());


        $creditor = $creditTransferTransactionInformation->addChild("Cdtr");
        $creditor->addChild("Nm", $this->getCreditorName());

        if ( $this->getCreditorAddressLine() && $this->getCreditorCountry()) {
            $postalAddress = $creditor->addChild('PstlAdr');
            $postalAddress->addChild('AdrLine', $this->getCreditorAddressLine());
            $postalAddress->addChild('Ctry', $this->getCreditorCountry());
        }

        $creditTransferTransactionInformation->addChild('CdtrAcct')
            ->addChild('Id')
            ->addChild('IBAN', $this->getIBAN());

        $remittance_information = $creditTransferTransactionInformation->addChild('RmtInf');
        $structured = $remittance_information->addChild('Strd');
        $creditor_reference_information = $structured->addChild('CdtrRefInf');
        $creditor_reference_information->addChild('Ref', $this->getCreditInvoiceReference());
        $structured->addChild('AddtlRmtInf', $this->getCreditInvoice());

        if ( $this->getCreditInvoiceCode() ) {
            $creditTransferTransactionInformation->addChild('Purp')
                ->addChild('Cd', $this->getCreditInvoiceCode());
        }

        return $creditTransferTransactionInformation;

    }
}