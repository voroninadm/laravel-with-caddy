<script setup>
import TextInput from "@/components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/components/InputError.vue";
import InputLabel from "@/components/InputLabel.vue";
import {vMaska} from "maska/vue"
import NumberInput from "@/components/NumberInput.vue";
import PrimaryButton from "@/components/PrimaryButton.vue";
import Modal from "@/components/Modal.vue";
import {ref} from "vue";
import SecondaryButton from "@/components/SecondaryButton.vue";
import NTLlogo from "@/components/icons/NTLlogo.vue";
import {showPhpCurrentDate} from "@/common/dateTimeHelper.js";

const about_form = useForm({
    vacancy: '',
    last_name: '',
    first_name: '',
    middle_name: '',
    birth_date: '2000-01-01',
    address: '',
    phone: '',
    nationality: '',
    sex: '',
    education: '',
    speciality: '',
    marital_status: '',
    children: false,
    narcotics: '',
    hobby: '',
    trips: '',
    driver: '',
    criminal: '',
    contract: '',
    previous_workplace: '',
    previous_workplace_long: '',
    info_source: '',
    languages: '',
    salary: null,
    salary_min: null,
    date_workout: showPhpCurrentDate()
})

function capitalizeFirstLetter(field) {
    about_form[field] = about_form[field].charAt(0).toUpperCase() + about_form[field].slice(1);
}

const emit = defineEmits('finishAbout');

const isModalOpen = ref(false);

const submitQuestionnaire = () => {
    emit('finishAbout', about_form);
}
</script>

<template>
    <section class="w-full h-full flex flex-col items-center bg-stone-200/50">
        <div class="pt-6 pb-20 my-10 w-8/12 bg-white drop-shadow-sm">
            <NTLlogo class="mx-auto w-25" />
            <h1 class="text-center font-bold p-2 text-3xl text-gray-500">Анкета соискателя</h1>
            <form @submit.prevent="isModalOpen = true" action="#">

                <div class="flex flex-col gap-5 drop-shadow-sm">
                    <div
                        class="flex items-center justify-center gap-8 mb-8 bg-white p-2 w-8/12 mx-auto shadow-sm shadow-gray-400  rounded-lg">
                        <input-label for="vacancy" class="text-gray-500">на должность</input-label>
                        <TextInput
                            id="vacancy"
                            placeholder="... например, машинист экструдера"
                            class="h-6 w-2/4 text-sm"
                            v-model="about_form.vacancy"
                            autocomplete="off"
                            required
                        />
                        <InputError class="mt-2" :message="about_form.errors.vacancy"/>
                    </div>

                    <div class="flex flex-col gap-4 bg-white p-3 w-8/12 mx-auto shadow-sm shadow-gray-400 rounded-lg">
                        <div class="flex items-center">
                            <input-label class="w-3/12" for="last_name">Фамилия</input-label>
                            <TextInput
                                id="last_name"
                                class="h-6 w-full text-sm w-full capitalize"
                                v-model="about_form.last_name"
                                autocomplete="off"
                                placeholder="Иванов"
                                required
                                @input="capitalizeFirstLetter('last_name')"
                            />
                            <InputError class="mt-2" :message="about_form.errors.last_name"/>
                        </div>
                        <div class="flex items-center">
                            <input-label class="w-3/12" for="first_name">Имя</input-label>
                            <TextInput
                                id="first_name"
                                class="h-6 text-sm w-full capitalize"
                                v-model="about_form.first_name"
                                autocomplete="off"
                                placeholder="Иван"
                                required
                                @input="capitalizeFirstLetter('first_name')"
                            />
                            <InputError class="mt-2" :message="about_form.errors.first_name"/>
                        </div>

                        <div class="flex items-center">
                            <input-label class="w-3/12" for="middle_name">Отчество</input-label>
                            <TextInput
                                id="middle_name"
                                placeholder="Иванович"
                                class="h-6 text-sm w-full capitalize"
                                v-model="about_form.middle_name"
                                autocomplete="off"
                                @input="capitalizeFirstLetter('middle_name')"
                            />
                            <InputError class="mt-2" :message="about_form.errors.middle_name"/>
                        </div>

                        <div class="flex items-center">
                            <input-label class="w-3/12" for="nationality">Гражданство</input-label>
                            <TextInput
                                id="nationality"
                                class="h-6 text-sm w-full"
                                placeholder="российское"
                                v-model="about_form.nationality"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="about_form.errors.nationality"/>
                        </div>

                        <div class="flex w-full justify-between px-20">
                            <div class="flex items-center gap-4">
                                <input-label for="birth_date">Дата рождения</input-label>
                                <input type="date"
                                       id="birth_date"
                                       class=" text-sm py-0.5 px-1 text-gray-600 px-5 border-gray-200 flex items-center  rounded-lg h-6 appearance-none placeholder:items-center placeholder:justify-center border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
                                       v-model="about_form.birth_date"
                                       autocomplete="off"
                                       required
                                       :max="showPhpCurrentDate()"
                                />
                                <InputError class="mt-2" :message="about_form.errors.birth_date"/>
                            </div>
                            <div class="flex items-center gap-4">
                                <input-label>Пол:</input-label>
                                <div class="flex gap-10 w-full">
                                    <label class="radio-label text-gray-700 text-sm flex items-center gap-2">
                                        <input type="radio" class="input-radio gray" name="sex" v-model="about_form.sex"
                                               value="Мужской" required>
                                        Мужской</label>
                                    <label class="radio-label text-gray-700 text-sm flex items-center gap-2">
                                        <input type="radio" class="input-radio gray" name="sex" v-model="about_form.sex"
                                               value="Женский">
                                        Женский
                                    </label>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="flex flex-col gap-4 bg-white p-3 w-8/12 mx-auto shadow-sm shadow-gray-400 rounded-lg">
                        <div class="flex gap-2 items-center">
                            <input-label class="w-5/12" for="address">Фактический адрес проживания</input-label>
                            <TextInput v-model="about_form.address" id="address" autocomplete="off"
                                       placeholder="ЛО, г.Светогорск, Рощинская-22"
                                       class="h-6 min-h-7 w-full flex justify-center text-sm " required></TextInput>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label class="w-5/12" for="phone">Контактный номер телефона</input-label>
                            <input v-model="about_form.phone" type="tel"
                                   autocomplete="off"
                                   v-maska="'+7 (###) ###-##-##'"
                                   id="phone"
                                   class="h-6 text-sm w-full border-gray-300 rounded-lg border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
                                   placeholder="+7 (000) 123-45-67" required>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label for="education" class="w-5/12">Образование</input-label>
                            <select id="education" v-model="about_form.education" required
                                    class="h-6 w-full flex items-center justify-center px-5 py-0.5 appearance-none rounded-lg border-gray-300 text-sm border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs">
                                <option class="text-sm" value="Основное общее (9 классов)">Основное общее (9 классов)
                                </option>
                                <option class="text-sm" value="Среднее общее (11 классов)">Среднее общее (11 классов)
                                </option>
                                <option class="text-sm" value="Начальное профессиональное">Начальное профессиональное
                                </option>
                                <option class="text-sm" value="Среднее профессиональное">Среднее профессиональное
                                </option>
                                <option class="text-sm" value="Высшее">Высшее</option>
                            </select>
                            <InputError class="mt-2" :message="about_form.errors.education"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label for="speciality" class="w-5/12">Специальность по диплому</input-label>
                            <TextInput
                                id="speciality"
                                class="text-sm w-full h-6 capitalize"
                                v-model="about_form.speciality"
                                autocomplete="off"
                            />
                            <InputError class="mt-2" :message="about_form.errors.speciality"/>
                        </div>
                        <div class="flex gap-2 items-center justify-center">
                            <input-label class="w-5/12" for="languages">Владеете ли Вы знанием иностранных языков?
                            </input-label>
                            <TextInput v-model="about_form.languages" autocomplete="off" id="languages"
                                       placeholder="... если да, то перечислите какими"
                                       class="w-full text-sm h-6"></TextInput>
                            <InputError class="mt-2" :message="about_form.errors.languages"/>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input-label for="marital_status" class="w-5/12">Семейное положение</input-label>
                            <select id="marital_status" v-model="about_form.marital_status" required
                                    class="h-6 w-full flex items-center justify-center px-5 py-0.5 appearance-none rounded-lg border-gray-300 text-sm border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs">
                                <option class="text-sm" value="Холост/Не замужем">Холост/Не замужем</option>
                                <option class="text-sm" value="Женат / Замужем">Женат / Замужем</option>
                                <option class="text-sm" value="В разводе">В разводе</option>
                                <option class="text-sm" value="Вдова / Вдовец">Вдова / Вдовец</option>
                                <option class="text-sm" value="Все сложно">Все сложно</option>
                            </select>
                            <InputError class="mt-2" :message="about_form.errors.marital_status"/>
                        </div>
                        <div class="flex gap-2 items-center justify-center">
                            <input-label class="w-5/12" for="hobby">Есть ли у Вас хобби, увлечения?
                            </input-label>
                            <TextInput v-model="about_form.hobby" autocomplete="off" id="hobby"
                                       placeholder="... если да, то перечислите какие"
                                       class="w-full text-sm h-6"></TextInput>
                            <InputError class="mt-2" :message="about_form.errors.hobby"/>
                        </div>
                    </div>

                    <div
                        class="flex flex-col gap-5 bg-white py-3 px-20 w-8/12 mx-auto shadow-sm shadow-gray-400 rounded-lg">
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">Есть ли у Вас дети, не достигшие 18 лет</input-label>
                            <div class="flex gap-5">
                                <label class="radio-label text-sm flex gap-2 items-center">
                                    <input type="radio" name="children" class="input-radio gray"
                                           v-model="about_form.children"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="radio-label text-sm flex gap-2 items-center">
                                    <input type="radio" name="children" class="input-radio gray"
                                           v-model="about_form.children"
                                           value="Нет">
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.children"/>
                        </div>
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">Привлекались ли вы когда-либо к уголовной ответственности
                            </input-label>
                            <div class="flex gap-5">
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="criminal" class="input-radio gray"
                                           v-model="about_form.criminal"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="criminal" class="input-radio gray"
                                           v-model="about_form.criminal"
                                           value="Нет">
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.criminal"/>
                        </div>
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">За последние 2 года состояли ли Вы на
                                государственной службе/
                                службе по контракту
                            </input-label>
                            <div class="flex gap-5">
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="contract" class="input-radio gray"
                                           v-model="about_form.contract"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="contract" class="input-radio gray"
                                           v-model="about_form.contract"
                                           value="Нет" required>
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.info_source"/>
                        </div>
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">Готовы ли Вы пройти медицинский тест на определение
                                наркотических
                                веществ и/или алкоголя
                            </input-label>
                            <div class="flex gap-5">
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="narcotics" class="input-radio gray"
                                           v-model="about_form.narcotics"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="narcotics" class="input-radio gray"
                                           v-model="about_form.narcotics"
                                           value="Нет">
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.narcotics"/>
                        </div>
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">Готовы ли вы к командировкам
                            </input-label>
                            <div class="flex gap-5">
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="trips" class="input-radio gray"
                                           v-model="about_form.trips"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="trips" class="input-radio gray"
                                           v-model="about_form.trips"
                                           value="Нет" required>
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.info_source"/>
                        </div>
                        <div class="flex items-center justify-between">
                            <input-label class="w-9/12">Есть ли у Вас водительские права категории В</input-label>
                            <div class="flex gap-5">
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="driver" class="input-radio gray"
                                           v-model="about_form.driver"
                                           value="Да" required>
                                    Да
                                </label>
                                <label class="flex gap-2 items-center radio-label text-sm">
                                    <input type="radio" name="driver" class="input-radio gray"
                                           v-model="about_form.driver"
                                           value="Нет">
                                    Нет
                                </label>
                            </div>
                            <InputError class="mt-2" :message="about_form.errors.driver"/>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5 bg-white p-3 w-8/12 mx-auto shadow-sm shadow-gray-400 rounded-lg">
                        <div class="flex gap-2 items-center justify-center">
                            <input-label class="w-5/12" for="previous_workplace">Предыдущее место работы</input-label>
                            <TextInput v-model="about_form.previous_workplace" id="previous_workplace"
                                       placeholder="... если было - название организации, город"
                                       class="w-full text-sm h-6" autocomplete="off" required></TextInput>
                            <InputError class="mt-2" :message="about_form.errors.previous_workplace_long"/>
                        </div>
                        <div class="flex gap-2 items-center justify-center">
                            <input-label class="w-5/12" for="previous_workplace_long">Стаж на предыдущем месте работы
                            </input-label>
                            <TextInput v-model="about_form.previous_workplace_long" id="previous_workplace_long"
                                       placeholder="... укажите сколько дней, месяцев или лет"
                                       class="w-full text-sm h-6" autocomplete="off" ></TextInput>
                            <InputError class="mt-2" :message="about_form.errors.previous_workplace_long"/>
                        </div>
                    </div>

                    <div class="flex flex-col gap-5 bg-white p-3 w-8/12 mx-auto shadow-sm shadow-gray-400 rounded-lg">
                        <div class="flex gap-4 items-center justify-between">
                            <input-label class="w-10/12" for="info_source">Из каких источников Вы узнали о вакансии?
                            </input-label>
                            <TextInput
                                id="info_source"
                                class="h-6 text-sm w-full"
                                v-model="about_form.info_source"
                                placeholder="... социальные сети, друзья, сайт компании, газета"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="about_form.errors.info_source"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <input-label class="w-10/12" for="salary">Какой уровень заработной платы соответствовал бы
                                Вашей
                                профессиональной компетенции
                            </input-label>
                            <NumberInput
                                id="salary"
                                step="5000"
                                class="h-6 text-sm w-full"
                                v-model="about_form.salary"
                                placeholder="... тысяч рублей"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="about_form.errors.salary"/>
                        </div>
                        <div class="flex items-center gap-4">
                            <input-label class="w-10/12" for="salary_min">Каков минимальный уровень заработной платы для
                                вас
                            </input-label>
                            <NumberInput
                                id="salary_min"
                                step="5000"
                                class="h-6 text-sm w-full"
                                v-model="about_form.salary_min"
                                placeholder="... тысяч рублей"
                                autocomplete="off"
                                required
                            />
                            <InputError class="mt-2" :message="about_form.errors.salary_min"/>
                        </div>

                        <div class="flex items-center justify-center gap-10 p-1">
                            <input-label for="date_workout">Дата, с которой Вы могли бы приступить к работе
                            </input-label>
                            <input type="date"
                                   id="date_workout"
                                   class="text-sm py-0.5 text-gray-700 px-5 border-gray-200 flex items-center justify-center rounded-lg h-6 appearance-none placeholder:items-center placeholder:justify-center border-gray-300 focus:border-orange-600 focus:ring-orange-600 rounded-md shadow-xs"
                                   v-model="about_form.date_workout"
                                   autocomplete="off"
                                   required
                                   :min="showPhpCurrentDate()"
                            />
                            <InputError class="mt-2" :message="about_form.errors.date_workout"/>
                        </div>

                        <div class="items-center justify-center m-auto w-4/12 p-2">
                            <primary-button class="w-full justify-center items-center">Перейти к тестированию
                            </primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <Modal :show="isModalOpen" :maxWidth="'xl'">
        <div class="p-3 flex flex-col text-center items-center text-sm">
            <p class="text-lg p-4 text-gray-600">Вы уверены, что хотите завершить заполнение анкеты и перейти к
                тестированию?</p>
            <div class="flex gap-10 p-4">
                <primary-button @click="submitQuestionnaire"> Перейти к тесту</primary-button>
                <secondary-button @click="isModalOpen = false"> Вернуться к анкете</secondary-button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
input[type="date"]::placeholder {
    text-align: center;
}

.input-radio.gray:checked {
    box-shadow: 1px 1px 1px 1.5px rgba(211, 124, 124, 0.86);
    background-color: rgba(140, 135, 135, 0.7);
    padding: 1px;
    border: 1px solid #a29999;
}
</style>
