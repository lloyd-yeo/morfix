<?php

namespace App\Console\Commands;

use App\BraintreeTransaction;
use App\StripeCharge;
use App\StripeDetail;
use App\StripeInvoice;
use App\UserAffiliates;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;

class UpdatePendingCommissionPayable extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'commission:updatependingpayable {email?}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update the payable comission for users paid.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try {
			$start_date = '2017-12-01 00:00:00';
			$end_date   = '2017-12-31 23:59:59';
			$users      = User::where('tier', '>=', 2);
			//		                  ->where('last_pay_out_date', '2017-12-25 00:00:00');

			if ($this->argument('email') != NULL) {
				$users = $users->where('email', $this->argument('email'));
			}
			$users      = $users->get();
			$user_comms = [];

			$this->alert($users->count() . " users");

			foreach ($users as $user) {

				$date_to_retrieve_invoices_from = NULL;

				if ($user->last_pay_out_date !== NULL) {
					$last_pay_out_date              = $user->last_pay_out_date;
					$last_pay_out_date              = \Carbon\Carbon::parse($user->last_pay_out_date);
					$date_to_retrieve_invoices_from = $last_pay_out_date->day(1)->hour(0)->minute(0);
				} else {
					$date_to_retrieve_invoices_from = \Carbon\Carbon::parse($start_date);
				}

				$pending_comms   = 0;
				$user_affiliates = UserAffiliates::where('referrer', $user->user_id)->get();

				foreach ($user_affiliates as $user_affiliate) {
					$affiliate = User::find($user_affiliate->referred);
					if ($affiliate == NULL) {
						continue;
					} else {
						$stripe_details = StripeDetail::where('email', $affiliate->email)->get();
						if ($stripe_details->count() > 0) {
							foreach ($stripe_details as $stripe_detail) {
								$stripe_id = $stripe_detail->stripe_id;

								$stripe_invoices = StripeInvoice::where('stripe_id', $stripe_id)
								                                ->where('invoice_date', '>=', $date_to_retrieve_invoices_from)
								                                ->where('invoice_date', '<=', $end_date)
								                                ->where('paid', 1)
								                                ->get();

								foreach ($stripe_invoices as $stripe_invoice) {
									$stripe_charge = StripeCharge::where('charge_id', $stripe_invoice->charge_id)
									                             ->where('invoice_id', $stripe_invoice->invoice_id)
									                             ->where('paid', 1)
									                             ->where('refunded', 0)
									                             ->first();

									if ($stripe_charge != NULL) {
										switch ($stripe_invoice->subscription_id) {
											case '0137':
												if ($user->tier > 1) {
													$pending_comms += 20;
													$this->line($user->email . "," .
														$affiliate->email . ",20," .
														$stripe_charge->charge_id . "," .
														$stripe_invoice->invoice_id . ',' . $stripe_charge->charge_created . ",stripe");
												}
												break;
											case '0297':
												if ($user->tier > 10) {
													$pending_comms += 50;
													$this->line($user->email . "," .
														$affiliate->email . ",50," .
														$stripe_charge->charge_id . "," .
														$stripe_invoice->invoice_id . ',' . $stripe_charge->charge_created . ",stripe");
												}
												break;
											case 'MX370':
												if ($user->tier > 2 && $user->tier != 12 && $user->tier != 22) {
													$pending_comms += 200;
													$this->line($user->email . "," .
														$affiliate->email . ",200," .
														$stripe_charge->charge_id . "," .
														$stripe_invoice->invoice_id . ',' . $stripe_charge->charge_created . ",stripe");
												}
												break;
											case 'MX297':
												if ($user->tier > 2 && $user->tier != 12 && $user->tier != 22) {
													$pending_comms += 120;
													$this->line($user->email . "," .
														$affiliate->email . ",120," .
														$stripe_charge->charge_id . "," .
														$stripe_invoice->invoice_id . ',' . $stripe_charge->charge_created . ",stripe");
												}
												break;
											case 'MX970':
												if ($user->tier > 20) {
													$pending_comms += 500;
													$this->line($user->email . "," .
														$affiliate->email . ",500," .
														$stripe_charge->charge_id . "," .
														$stripe_invoice->invoice_id . ',' . $stripe_charge->charge_created . ",stripe");
												}
												break;
											case '0167':
												break;
											default:
												break;
										}
									}
								}
							}
						}

						if ($affiliate->braintree_id != NULL) {
							//retrieve braintree stuff here
							$braintree_transactions = BraintreeTransaction::select('sub_id')
							                                              ->distinct()
							                                              ->where('braintree_id', $affiliate->braintree_id)
							                                              ->where('created_at', '>=', $date_to_retrieve_invoices_from)
							                                              ->where('created_at', '<=', $end_date)
							                                              ->where('status', '!=', 'processor_declined')
							                                              ->get();

							foreach ($braintree_transactions as $braintree_transaction) {
								//check if there is refund for transaction
								//							$this->alert($braintree_transaction->sub_id);

								$braintree_transactions_cancelled = BraintreeTransaction::where('sub_id', $braintree_transaction->sub_id)
								                                                        ->where('created_at', '>=', $date_to_retrieve_invoices_from)
								                                                        ->where('created_at', '<=', $end_date)
								                                                        ->where('type', 'credit')
								                                                        ->first();
								if ($braintree_transactions_cancelled == NULL) {

									$braintree_transactions_completed = BraintreeTransaction::where('sub_id', $braintree_transaction->sub_id)
									                                                        ->where('created_at', '>=', $date_to_retrieve_invoices_from)
									                                                        ->where('created_at', '<=', $end_date)
									                                                        ->where('type', 'sale')
									                                                        ->first();

									//								$this->alert($braintree_transactions_completed->sub_id . " " . $braintree_transactions_completed->plan_id);

									if ($braintree_transactions_completed == NULL) {
										dump($date_to_retrieve_invoices_from);
										dump($end_date);
										dump($braintree_transaction);
									}

									switch ($braintree_transactions_completed->plan_id) {
										case '0137':
											if ($user->tier > 1) {
												$pending_comms += 20;
												$this->line($user->email . "," .
													$affiliate->email . ",20," .
													$braintree_transactions_completed->sub_id . "," . $braintree_transactions_completed->created_at . ",braintree");
											}
											break;
										case '0297':
											if ($user->tier > 10) {
												$pending_comms += 50;
												$this->line($user->email . "," .
													$affiliate->email . ",50," .
													$braintree_transactions_completed->sub_id . "," . $braintree_transactions_completed->created_at . ",braintree");
											}
											break;
										case 'MX370':
											if ($user->tier > 2 && $user->tier != 12 && $user->tier != 22) {
												$pending_comms += 200;
												$this->line($user->email . "," .
													$affiliate->email . ",200," .
													$braintree_transactions_completed->sub_id . "," . $braintree_transactions_completed->created_at . ",braintree");
											}
											break;
										case 'MX297':
											if ($user->tier > 2 && $user->tier != 12 && $user->tier != 22) {
												$pending_comms += 120;
												$this->line($user->email . "," .
													$affiliate->email . ",120," .
													$braintree_transactions_completed->sub_id . "," . $braintree_transactions_completed->created_at . ",braintree");
											}
											break;
										case 'MX970':
											if ($user->tier > 20) {
												$pending_comms += 500;
												$this->line($user->email . "," .
													$affiliate->email . ",500," .
													$braintree_transactions_completed->sub_id . "," . $braintree_transactions_completed->created_at . ",braintree");
											}
											break;
										case '0167':
											break;
										default:
											break;
									}

								}
							}
						} else {

						}

					}
				}
				$user->pending_commission_payable = $pending_comms;
				//			$user->save();
				if ($pending_comms > 0) {
					$user_comms[] = $user->email . ',' . $pending_comms;
				}

			}

			foreach ($user_comms as $user_comm) {
				$this->line($user_comm);
			}
		}
		catch (\Exception $ex) {
			dump($ex);
		}

	}

}
