<!-- Create Personnel Modal for School Head - Matches Admin Form Structure -->
<div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('personnelModal', () => ({
        show: false,
        activeTab: 'personal',
        positions: [],
        salaryGrades: [],
        formData: {
            // Personal Information (matching PersonnelCreate.php)
            first_name: '',
            middle_name: '',
            last_name: '',
            name_ext: '',
            sex: '',
            civil_status: '',
            citizenship: '',
            blood_type: '',
            height: '',
            weight: '',
            date_of_birth: '',
            place_of_birth: '',
            email: '',
            tel_no: '',
            mobile_no: '',

            // Work Information (matching PersonnelCreate.php)
            personnel_id: '',
            school_id: '{{ Auth::user()->personnel->school_id ?? "" }}',
            position_id: '',
            appointment: '',
            fund_source: '',
            salary_grade_id: '',
            step_increment: '',
            category: '',
            job_status: '',
            employment_start: '',
            employment_end: '',
            pantilla_of_personnel: '',
            is_solo_parent: false,

            // Government Information (matching PersonnelCreate.php)
            tin: '',
            sss_num: '',
            gsis_num: '',
            philhealth_num: '',
            pagibig_num: '',
        },

        async init() {
            window.addEventListener('open-create-personnel-modal', () => this.open());
            await this.fetchDropdownData();
        },

        async fetchDropdownData() {
            try {
                // Fetch positions
                const posResponse = await fetch('/api/positions');
                this.positions = await posResponse.json();

                // Fetch salary grades
                const gradeResponse = await fetch('/api/salary-grades');
                if (gradeResponse.ok) {
                    this.salaryGrades = await gradeResponse.json();
                }
            } catch (error) {
                console.error('Error fetching dropdown data:', error);
            }
        },

        open() {
            this.show = true;
            this.resetForm();
        },

        close() {
            this.show = false;
        },

        resetForm() {
            this.activeTab = 'personal';
            Object.keys(this.formData).forEach(key => {
                if (key === 'school_id') {
                    this.formData[key] = '{{ Auth::user()->personnel->school_id ?? "" }}';
                } else if (key === 'is_solo_parent') {
                    this.formData[key] = false;
                } else {
                    this.formData[key] = '';
                }
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
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner"></span> Creating...';
                submitBtn.disabled = true;

                const response = await fetch('/school-head/personnel/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (data.success) {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { type: 'success', message: 'Personnel created successfully!' }
                    }));
                    this.close();
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: { type: 'error', message: data.message || 'Error creating personnel' }
                    }));
                }
            } catch (error) {
                console.error('Error:', error);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: { type: 'error', message: 'An error occurred while creating personnel' }
                }));
            } finally {
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.innerHTML = 'Create Personnel';
                    submitBtn.disabled = false;
                }
            }
        }
    }));
});
</script>

<div x-data="personnelModal" class="fixed inset-0 z-50 overflow-y-auto" x-show="show" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-show="show" @click="close()"></div>

        <!-- Modal panel -->
        <div class="relative inline-block w-full max-w-6xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
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
                    <template x-for="(tab, index) in [{name: 'Personal', key: 'personal'}, {name: 'Work', key: 'work'}, {name: 'Gov Info', key: 'government'}]" :key="index">
                        <div class="flex items-center flex-1">
                            <div class="flex items-center">
                                <div :class="activeTab === tab.key ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600'"
                                     class="rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                                    <span x-text="index + 1"></span>
                                </div>
                                <span class="ml-2 text-sm font-medium"
                                      :class="activeTab === tab.key ? 'text-blue-600' : 'text-gray-500'"
                                      x-text="tab.name">
                                </span>
                            </div>
                            <div x-show="index < 2" class="flex-1 h-1 mx-4"
                                 :class="(activeTab === 'work' && index === 0) || (activeTab === 'government' && index <= 1) ? 'bg-blue-600' : 'bg-gray-300'">
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Modal body -->
            <div class="max-h-[70vh] overflow-y-auto p-6">
                <form @submit.prevent="submit()">
                    <!-- Personal Information Tab -->
                    <div x-show="activeTab === 'personal'" x-transition>
                        <h4 class="mb-4 text-lg font-semibold">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Name Fields -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" x-model="formData.middle_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name Extension</label>
                                <input type="text" x-model="formData.name_ext" placeholder="e.g., Jr., Sr., III" maxlength="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Gender and Civil Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sex <span class="text-red-500">*</span></label>
                                <select x-model="formData.sex" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Civil Status <span class="text-red-500">*</span></label>
                                <select x-model="formData.civil_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="widowed">Widowed</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="seperated">Separated</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>

                            <!-- Birth Details -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" x-model="formData.date_of_birth" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Place of Birth <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.place_of_birth" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Citizenship <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.citizenship" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Physical Attributes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Height in CM</label>
                                <input type="text" x-model="formData.height" placeholder="e.g., 170 cm" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Weight in KG</label>
                                <input type="text" x-model="formData.weight" placeholder="e.g., 65 kg" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                                <select x-model="formData.blood_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
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

                            <!-- Contact Information -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" x-model="formData.email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telephone No.</label>
                                <input type="tel" x-model="formData.tel_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile No.</label>
                                <input type="tel" x-model="formData.mobile_no" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Work Information Tab -->
                    <div x-show="activeTab === 'work'" x-transition>
                        <h4 class="mb-4 text-lg font-semibold">Work Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Employee ID -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.personnel_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- School (disabled, pre-filled) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">School <span class="text-red-500">*</span></label>
                                <input type="text" value="{{ Auth::user()->personnel->school->school_name ?? 'Your School' }}" disabled class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg">
                            </div>

                            <!-- Position -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position <span class="text-red-500">*</span></label>
                                <select x-model="formData.position_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Position</option>
                                    <template x-for="position in positions" :key="position.id">
                                        <option :value="position.id" x-text="position.title"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Appointment -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Appointment <span class="text-red-500">*</span></label>
                                <select x-model="formData.appointment" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="regular">Regular</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="temporary">Temporary</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>

                            <!-- Fund Source -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fund Source <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.fund_source" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Salary Grade -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Salary Grade <span class="text-red-500">*</span></label>
                                <select x-model="formData.salary_grade_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <template x-for="grade in salaryGrades" :key="grade.id">
                                        <option :value="grade.id" x-text="'Grade ' + grade.grade"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Step Increment -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Step Increment <span class="text-red-500">*</span></label>
                                <select x-model="formData.step_increment" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="1">Step 1</option>
                                    <option value="2">Step 2</option>
                                    <option value="3">Step 3</option>
                                    <option value="4">Step 4</option>
                                    <option value="5">Step 5</option>
                                    <option value="6">Step 6</option>
                                    <option value="7">Step 7</option>
                                    <option value="8">Step 8</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select x-model="formData.category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="SDO Personnel">SDO Personnel</option>
                                    <option value="School Head">School Head</option>
                                    <option value="Elementary School Teacher">Elementary School Teacher</option>
                                    <option value="Junior High School Teacher">Junior High School Teacher</option>
                                    <option value="Senior High School Teacher">Senior High School Teacher</option>
                                    <option value="School Non-teaching Personnel">School Non-teaching Personnel</option>
                                </select>
                            </div>

                            <!-- Job Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Job Status <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.job_status" placeholder="e.g., Active, On Leave" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Employment Dates -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employment Start <span class="text-red-500">*</span></label>
                                <input type="date" x-model="formData.employment_start" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employment End</label>
                                <input type="date" x-model="formData.employment_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Pantilla -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pantilla of Personnel</label>
                                <input type="text" x-model="formData.pantilla_of_personnel" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Solo Parent -->
                            <div class="flex items-center mt-6">
                                <input type="checkbox" x-model="formData.is_solo_parent" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-700">Is Solo Parent</label>
                            </div>
                        </div>
                    </div>

                    <!-- Government Information Tab -->
                    <div x-show="activeTab === 'government'" x-transition>
                        <h4 class="mb-4 text-lg font-semibold">Government Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">TIN <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.tin" maxlength="12" placeholder="12 digits" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SSS Number</label>
                                <input type="text" x-model="formData.sss_num" maxlength="10" placeholder="10 digits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">GSIS Number</label>
                                <input type="text" x-model="formData.gsis_num" maxlength="11" placeholder="11 digits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">PhilHealth Number</label>
                                <input type="text" x-model="formData.philhealth_num" maxlength="12" placeholder="12 digits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pag-IBIG Number</label>
                                <input type="text" x-model="formData.pagibig_num" maxlength="12" placeholder="12 digits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                class="px-6 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors flex items-center">
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
