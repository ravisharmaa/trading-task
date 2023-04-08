import axios from "axios";
export default class ApiRequest {
    async getCompanySymbols(symbol) {
        let data = await axios.get('/api/company-symbols?symbol='+ symbol)
        return data.data
    }

    async getHistoricalData(formObject) {
        return await axios.post('/historical-quote', formObject)
    }
}
