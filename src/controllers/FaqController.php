<?php

namespace Abs\FaqPkg;
use Abs\Basic\Address;
use Abs\FaqPkg\Faq;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class FaqController extends Controller {

	public function __construct() {
		$this->data['theme'] = config('custom.admin_theme');
	}

	public function getFaqList(Request $request) {
		$faqs = Faq::withTrashed()
			->select([
				'faqs.id',
				'faqs.question',
				DB::raw('faqs.deleted_at as status'),
			])
			->where('faqs.company_id', Auth::user()->company_id)
			->where(function ($query) use ($request) {
				if (!empty($request->question)) {
					$query->where('faqs.question', 'LIKE', '%' . $request->question . '%');
				}
			})
			->orderby('faqs.id', 'desc');

		return Datatables::of($faqs)
			->addColumn('question', function ($faq) {
				$status = $faq->status ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $faq->question;
			})
			->addColumn('action', function ($faq) {
				$img1 = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow.svg');
				$img1_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow-active.svg');
				$img2 = asset('public/themes/' . $this->data['theme'] . '/img/content/table/eye.svg');
				$img2_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/eye-active.svg');
				$img_delete = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-default.svg');
				$img_delete_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-active.svg');
				$output = '';
				$output .= '<a href="#!/faq-pkg/faq/edit/' . $faq->id . '" id = "" ><img src="' . $img1 . '" alt="Edit" class="img-responsive" onmouseover=this.src="' . $img1_active . '" onmouseout=this.src="' . $img1 . '"></a>
					<a href="#!/faq-pkg/faq/view/' . $faq->id . '" id = "" ><img src="' . $img2 . '" alt="View" class="img-responsive" onmouseover=this.src="' . $img2_active . '" onmouseout=this.src="' . $img2 . '"></a>
					<a href="javascript:;"  data-toggle="modal" data-target="#faq-delete-modal" onclick="angular.element(this).scope().deleteRoleconfirm(' . $faq->id . ')" title="Delete"><img src="' . $img_delete . '" alt="Delete" class="img-responsive delete" onmouseover=this.src="' . $img_delete_active . '" onmouseout=this.src="' . $img_delete . '"></a>
					';
				return $output;
			})
			->make(true);
	}

	public function getFaqFormData(Request $r) {
		$id = $r->id;
		if (!$id) {
			$faq = new Faq;
			$action = 'Add';
		} else {
			$faq = Faq::withTrashed()->find($id);
			$action = 'Edit';
		}
		$this->data['faq'] = $faq;
		$this->data['action'] = $action;

		return response()->json($this->data);
	}

	public function saveFaq(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'code.required' => 'Faq Code is Required',
				'code.max' => 'Maximum 255 Characters',
				'code.min' => 'Minimum 3 Characters',
				'code.unique' => 'Faq Code is already taken',
				'name.required' => 'Faq Name is Required',
				'name.max' => 'Maximum 255 Characters',
				'name.min' => 'Minimum 3 Characters',
			];
			$validator = Validator::make($request->all(), [
				'question' => [
					'required:true',
					'max:255',
					'min:3',
					'unique:faqs,question,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
				'answer' => 'required|max:255|min:3',
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (!$request->id) {
				$faq = new Faq;
				$faq->created_by_id = Auth::user()->id;
				$faq->created_at = Carbon::now();
				$faq->updated_at = NULL;
			} else {
				$faq = Faq::withTrashed()->find($request->id);
				$faq->updated_by_id = Auth::user()->id;
				$faq->updated_at = Carbon::now();
			}
			$faq->fill($request->all());
			$faq->company_id = Auth::user()->company_id;
			if ($request->status == 'Inactive') {
				$faq->deleted_at = Carbon::now();
				$faq->deleted_by_id = Auth::user()->id;
			} else {
				$faq->deleted_by_id = NULL;
				$faq->deleted_at = NULL;
			}
			$faq->save();

			DB::commit();
			if (!($request->id)) {
				return response()->json([
					'success' => true,
					'message' => 'FAQ Added Successfully',
				]);
			} else {
				return response()->json([
					'success' => true,
					'message' => 'FAQ Updated Successfully',
				]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
			]);
		}
	}

	public function deleteFaq($id) {
		$delete_status = Faq::withTrashed()->where('id', $id)->forceDelete();
		if ($delete_status) {
			$address_delete = Address::where('address_of_id', 24)->where('entity_id', $id)->forceDelete();
			return response()->json(['success' => true]);
		}
	}
}
