<template>
    <div class="row">
        <div class="col-8 overflow-y-scroll p-2 mb-4 border rounded" style="height:550px;">

            <!--  Таблица отражает имена пользователей и объем залитого пользователем молока. 
        Распределение молока по цистернам проходит автомотически. 
        Есть возможность удаления записи с перерасчетом остатка в цистерне
    -->
            <table class="table table-striped table-hover ">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Объем порции</th>
                        <th scope="col">№ цистерны</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="milk in milks" :key="milk.id">
                        <td class="align-middle">{{ milk.id }}</td>
                        <td class="align-middle">{{ milk.name }}</td>
                        <td class="align-middle">{{ milk.volume }}</td>
                        <td class="align-middle">{{ milk.bottle_num }}</td>
                        <td>
                            <div class="row gap-1">

                                <button @click="deleteMilk(milk.id)" type="button"
                                    class="p-2 me-2 col border rounded-pill btn btn-danger">Удалить</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-4">
            <h2>Добавить молоко</h2>
            <!--  В форме ввода можно для объема молока нужно вводить целые числа (приводятся к целым).
    В случае превышения предельной величины объема цистерны, осуществляется проверка доступного объема следующей.
    Если превышение зафиксировано во всех цистернах, запись объема не производится. 
    -->
            <form @submit.prevent="submitForm" class="border rounded p-2">
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input class="form-control" type="text" id="name" v-model="newmilk.name" required />
                </div>
                <div class="mb-3">
                    <label for="volume" class="form-label">Объем порции в литрах</label>
                    <input class="form-control" id="volume" v-model="newmilk.volume" required />
                </div>

                <button type="submit" class="btn btn-primary">Зальем молокa!</button>

            </form>



        </div>
    </div>
    <!-- в данном элементе визуализируется наполнение цистерн молоком. 
    -->
    <div class="row p-2 border rounded">
        <div class="col text-bg-primary rounded-pill mx-1 py-3 position-relative">
            <img :src="'../storage/img/can.png'" class="img-fluid z-1 " />
            <p class="z-2 fs-1 position-absolute translate-middle top-50 start-50">{{ limits[0] }} л</p>
        </div>
        <div class="col text-bg-success bg-gradient rounded-pill mx-1 py-3 position-relative">
            <img :src="'../storage/img/can.png'" class="img-fluid z-1 " />
            <p class="z-2 fs-1 position-absolute translate-middle top-50 start-50">{{ limits[1] }} л</p>
        </div>
        <div class="col text-bg-primary rounded-pill mx-1 py-3 position-relative">
            <img :src="'../storage/img/can.png'" class="img-fluid z-1 " />
            <p class="z-2 fs-1 position-absolute translate-middle top-50 start-50">{{ limits[2] }} л</p>
        </div>
        <div class="col text-bg-success bg-gradient rounded-pill mx-1 py-3 position-relative">
            <img :src="'../storage/img/can.png'" class="img-fluid z-1 " />
            <p class="z-2 fs-1 position-absolute translate-middle top-50 start-50">{{ limits[3] }}л</p>
        </div>
        <div class="col text-bg-primary rounded-pill mx-1 py-3 position-relative">
            <img :src="'../storage/img/can.png'" class="img-fluid z-1 " />
            <p class="z-2 fs-1 position-absolute translate-middle top-50 start-50">{{ limits[4] }} л</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            milks: [],
            limits: [0, 0, 0, 0, 0],
            newmilk: {
                name: '',
                volume: 0,
                bottle_num: 1,
                total_left: 300
            }
        }
    },
    async created() {
        this.initialisation();
        this.getLimits();

    },
    methods: {
         // функция удаления записи
        async deleteMilk(id) {
            try {
                await axios.delete(`/api/milks/${id}`);
                this.milks = this.milks.filter(milk => milk.id !== id);
            } catch (error) {
                console.error(error);
            };
            this.getLimits();
        },

        // функция визуализации данных о  наполнении цистерн
        async getLimits() {
            this.limits = [0, 0, 0, 0, 0]
            try {
                const res = await axios.get('/limits');
                await res.data.forEach(element => {
                    this.limits[element['bottle_num'] - 1] = element['s_volume'];
                });

            } catch (error) {
                console.error(error);
            }


        },
        async submitForm() {
            try {
                await axios.post('/api/milks', this.newmilk);
                this.newmilk = {
                    name: '',
                    volume: 0,
                    bottle_num: 1,
                    total_left: 300
                }
            } catch (error) {
                console.error(error);
            }
            this.initialisation();
            this.getLimits();

        },
        // функция загрузки списка операций пополнения цистерн
        async initialisation() {
            try {
                const response = await axios.get('/api/milks');
                this.milks = response.data;
            } catch (error) {
                console.error(error);
            };


        }

    }
}
</script>