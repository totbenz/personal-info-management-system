<!-- Create Personnel Modal for School Head -->
<div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('personnelModal', () => ({
        show: false,
        activeTab: 'personal',
        positions: [],
        formData: {
            // Personal Info
            first_name: '',
            middle_name: '',
            last_name: '',
            name_extension: '',
            sex: '',
            civil_status: '',
            citizenship: '',
            height: '',
            weight: '',
            blood_type: '',
            gsis_id: '',
            pagibig_id: '',
            philhealth_id: '',
            sss_id: '',
            tin_id: '',
            residence_house_no: '',
            residence_street: '',
            residence_subdivision: '',
            residence_barangay: '',
            residence_city_municipality: '',
            residence_province: '',
            residence_zip_code: '',
            telephone_no: '',
            mobile_no: '',
            email_address: '',

            // Work Info
            position_id: '',
            employment_status: '',
            employment_start: '',
            employment_end: '',
            monthly_salary: '',
            salary_grade: '',
            step_increment: '',
            appointment_status: '',
            government_service: '',

            // Government Info
            agency_employee_no: '',
            biometric_no: '',
            school_id: '{{ Auth::user()->personnel->school_id ?? "" }}',
        },

        async init() {
            // Listen for open event
            window.addEventListener('open-create-personnel-modal', () => {
                this.open();
            });

            // Fetch positions
            await this.fetchPositions();
        },

        async fetchPositions() {
            try {
                const response = await fetch('/api/positions');
                const data = await response.json();
                this.positions = data.data || data;
            } catch (error) {
                console.error('Error fetching positions:', error);
            }
        },

        open() {
            this.show = true;
            this.resetForm();
        },

        close() {
            this.show = false;
            this.resetForm();
        },

        resetForm() {
            this.activeTab = 'personal';
            Object.keys(this.formData).forEach(key => {
                this.formData[key] = key === 'school_id' ? '{{ Auth::user()->personnel->school_id ?? "" }}' : '';
            });
        },

        nextTab() {
            const tabs = ['personal', 'work', 'government'];
            const currentIndex = tabs.indexOf(this.activeTab);
            if (currentIndex < tabs.length - 1) {
                this.activeTab = tabs[currentIndex + 1];
            }
        },

        prevTab() {
            const tabs = ['personal', 'work', 'government'];
            const currentIndex = tabs.indexOf(this.activeTab);
            if (currentIndex > 0) {
                this.activeTab = tabs[currentIndex - 1];
            }
        },

        async submit() {
            try {
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner"></span> Creating...';
                submitBtn.disabled = true;

                // Create FormData for file upload
                const formData = new FormData();
                Object.keys(this.formData).forEach(key => {
                    if (key === 'photo' && this.formData[key] && this.formData[key].files) {
                        formData.append(key, this.formData[key].files[0]);
                    } else {
                        formData.append(key, this.formData[key]);
                    }
                });

                const response = await fetch('/school-head/personnel/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            type: 'success',
                            message: 'Personnel created successfully!'
                        }
                    }));

                    // Close modal
                    this.close();

                    // Refresh the personnel table
                    window.dispatchEvent(new CustomEvent('personnel-created'));

                    // Redirect to personnel profile
                    window.location.href = `/personnel/${data.personnel_id}`;
                } else {
                    // Show error message
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            type: 'error',
                            message: data.message || 'Error creating personnel'
                        }
                    }));
                }
            } catch (error) {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        type: 'error',
                        message: 'An error occurred while creating personnel'
                    }
                }));
            } finally {
                // Reset button state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    }));
});
</script>

<div x-data="personnelModal" class="fixed inset-0 z-50 overflow-y-auto" x-show="show" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="show" @click="close()" aria-hidden="true"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block w-full max-w-6xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <!-- Modal header -->
            <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                <h3 class="text-xl font-semibold text-white">Create New Personnel</h3>
                <button @click="close()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Progress bar -->
            <div class="px-6 py-4 bg-gray-50 border-b">
                <div class="flex items-center justify-between">
                    <template x-for="(tab, index) in ['Personal', 'Work', 'Gov Info']" :key="index">
                        <div class="flex items-center">
                            <div class="flex items-center">
                                <div :class="activeTab === ['personal', 'work', 'government'][index] ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'"
                                     class="rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span class="ml-2 text-sm font-medium"
                                      :class="activeTab === ['personal', 'work', 'government'][index] ? 'text-blue-600' : 'text-gray-500'"
                                      x-text="tab">
                                </span>
                            </div>
                            <div x-show="index < 2" class="flex-1 h-1 mx-4"
                                 :class="activeTab === 'work' || activeTab === 'government' ? 'bg-blue-600' : 'bg-gray-300'">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal body -->
            <div class="max-h-[70vh] overflow-y-auto">
                <form @submit.prevent="submit()">
                    <!-- Personal Information Tab -->
                    <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="p-6">
                            <h4 class="mb-4 text-lg font-semibold">Personal Information</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="formData.first_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" x-model="formData.middle_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="formData.last_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name Extension</label>
                                    <select x-model="formData.name_extension" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Extension</option>
                                        <option value="Jr.">Jr.</option>
                                        <option value="Sr.">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                        <option value="IV">IV</option>
                                        <option value="V">V</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                                    <select x-model="formData.sex" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Sex</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Civil Status <span class="text-red-500">*</span></label>
                                    <select x-model="formData.civil_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Status</option>
                                        <option value="single">Single</option>
                                        <option value="married">Married</option>
                                        <option value="widowed">Widowed</option>
                                        <option value="separated">Separated</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Citizenship</label>
                                    <input type="text" x-model="formData.citizenship" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Height (cm)</label>
                                    <input type="number" x-model="formData.height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                    <input type="number" x-model="formData.weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Blood Type</label>
                                    <select x-model="formData.blood_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Blood Type</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">GSIS ID No.</label>
                                    <input type="text" x-model="formData.gsis_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">PAG-IBIG ID No.</label>
                                    <input type="text" x-model="formData.pagibig_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">PhilHealth ID No.</label>
                                    <input type="text" x-model="formData.philhealth_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">SSS ID No.</label>
                                    <input type="text" x-model="formData.sss_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">TIN ID No.</label>
                                    <input type="text" x-model="formData.tin_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Address Section -->
                            <h5 class="mt-6 mb-3 text-md font-semibold text-gray-800">Residential Address</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">House No.</label>
                                    <input type="text" x-model="formData.residence_house_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Street</label>
                                    <input type="text" x-model="formData.residence_street" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Subdivision</label>
                                    <input type="text" x-model="formData.residence_subdivision" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Barangay</label>
                                    <input type="text" x-model="formData.residence_barangay" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">City/Municipality</label>
                                    <input type="text" x-model="formData.residence_city_municipality" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Province</label>
                                    <input type="text" x-model="formData.residence_province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Zip Code</label>
                                    <input type="text" x-model="formData.residence_zip_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <h5 class="mt-6 mb-3 text-md font-semibold text-gray-800">Contact Information</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Telephone No.</label>
                                    <input type="tel" x-model="formData.telephone_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Mobile No. <span class="text-red-500">*</span></label>
                                    <input type="tel" x-model="formData.mobile_no" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" x-model="formData.email_address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Information Tab -->
                    <div x-show="activeTab === 'work'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="p-6">
                            <h4 class="mb-4 text-lg font-semibold">Work Information</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Position <span class="text-red-500">*</span></label>
                                    <select x-model="formData.position_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Position</option>
                                        <template x-for="position in positions" :key="position.id">
                                            <option :value="position.id" x-text="position.title"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Employment Status <span class="text-red-500">*</span></label>
                                    <select x-model="formData.employment_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Status</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="temporary">Temporary</option>
                                        <option value="contractual">Contractual</option>
                                        <option value="substitute">Substitute</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Employment Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" x-model="formData.employment_start" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Employment End Date</label>
                                    <input type="date" x-model="formData.employment_end" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Monthly Salary <span class="text-red-500">*</span></label>
                                    <input type="number" x-model="formData.monthly_salary" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Salary Grade</label>
                                    <input type="text" x-model="formData.salary_grade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Step Increment</label>
                                    <input type="number" x-model="formData.step_increment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Appointment Status <span class="text-red-500">*</span></label>
                                    <select x-model="formData.appointment_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select Status</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="temporary">Temporary</option>
                                        <option value="coterminous">Coterminous</option>
                                        <option value="substitute">Substitute</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" x-model="formData.government_service" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Government Service</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Government Information Tab -->
                    <div x-show="activeTab === 'government'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="p-6">
                            <h4 class="mb-4 text-lg font-semibold">Government Information</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Agency Employee No.</label>
                                    <input type="text" x-model="formData.agency_employee_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Biometric No.</label>
                                    <input type="text" x-model="formData.biometric_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">School <span class="text-red-500">*</span></label>
                                    <input type="text" value="{{ Auth::user()->personnel->school->school_name ?? '' }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                    <input type="hidden" x-model="formData.school_id" value="{{ Auth::user()->personnel->school_id ?? '' }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">District</label>
                                    <input type="text" value="{{ Auth::user()->personnel->school->district->district_name ?? '' }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Division</label>
                                    <input type="text" value="{{ Auth::user()->personnel->school->district->division->division_name ?? '' }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t">
                <button @click="prevTab()"
                        :disabled="activeTab === 'personal'"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Previous
                </button>

                <div class="flex space-x-2">
                    <template x-if="activeTab === 'government'">
                        <button @click="submit()"
                                id="submitBtn"
                                class="px-6 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Personnel
                        </button>
                    </template>

                    <template x-if="activeTab !== 'government'">
                        <button @click="nextTab()"
                                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                            Next
                        </button>
                    </template>

                    <button @click="close()"
                            type="button"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
.spinner {
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 1s linear infinite;
    display: inline-block;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</div>
