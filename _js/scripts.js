const aside = document.querySelector('Body > Aside')
const asideDivDiv = document.querySelector('Div#aside')

let sc = 'de';

function getTable(data = {}){ //console.log('data', data)
 axios({
  method: 'post',
  url: '/_php/t/i.php',
  data
 }).then((response) => {
  document.querySelector('Div#main').innerHTML = response.data
 }).then(() => {
  const aTr = document.querySelectorAll('TBody > Tr');
  let IoC
  aTr.forEach((v) => {
   v.childNodes.forEach((vv, kk) => {
    (kk === 0) && (IoC = vv.innerHTML);
    (kk === 1) && (v.setAttribute('onClick', `openForm('` + IoC + `')`));

    //<
    const cContactPersons = vv.querySelectorAll('div')
    if(cContactPersons.length > 0){
        Array.from(cContactPersons).forEach((contactPerson) => {
            if(contactPerson.dataset.date !== 'undefined'){
                const a_date = (contactPerson.dataset.date && contactPerson.dataset.date.indexOf('-') > -1) ? contactPerson.dataset.date.split('-') : '&mdash;';

                const then = new Date(a_date[0], a_date[1] - 1, a_date[2].substr(0, 2))
                const now = new Date()
                const difference = (now - then) / 1000 / 60 / 60 / 24
                //console.log(difference)

                if(difference > 45 && difference < 90){
                    contactPerson.style.color = 'orange'
                } else if(difference > 90){
                    contactPerson.style.color = 'red'
                }
            }
        })
    }
    //>
   })
  })
 }).then(() => {
  document.querySelectorAll('TBody > Tr').forEach((v) => {
      let aTd = v.children
      if(aTd[1].textContent === 'r'){
          aTd[1].style.backgroundColor = 'rgba(255, 0, 0, 0.12)';
          v.style.backgroundColor = 'rgba(255, 0, 0, 0.08)'
      } else if(aTd[1].textContent === 'g'){
          aTd[1].style.backgroundColor = 'rgba(0, 255, 0, 0.12)';
          v.style.backgroundColor = 'rgba(0, 255, 0, 0.08)'
      } else if(aTd[1].textContent === 'y'){
          aTd[1].style.backgroundColor = 'rgba(255, 255, 0, 0.12)';
          v.style.backgroundColor = 'rgba(255, 255, 0, 0.08)'
      } else if(aTd[1].textContent === 's'){
          aTd[1].style.backgroundColor = 'rgba(0, 0, 0, 0.12)';
          v.style.backgroundColor = 'rgba(0, 0, 0, 0.08)'
      }
  })
 }).then(() => {
  const cTh = document.querySelectorAll('THead Th')
  Array.from(cTh).forEach((v) => {
   v.addEventListener('click', () => { //console.log('v', v.innerHTML)
    sc = (sc === 'de') ? 'a' : 'de'; console.log('sc', sc)
    getTable({
     sort : v.innerHTML,
     sc
    })
   })
  })
 }).catch((error) => { console.log(error) })
}

function openForm(id){ console.log('openForm(?) > ' + id);
 aside.classList.add('active')

 axios({
  method: 'post',
  url: '/_php/f/i.php',
  data: {id: id}
 }).then((response) => {
  asideDivDiv.innerHTML = response.data
 }).then(() => {
  axios({
   method: 'post',
   url: '/_php/f/c/manufacturers.php',
   data: {}
  }).then((response) => {
   document.querySelector('#t_manufacturers').innerHTML += response.data

   getDataOfC({IoC: id})
   gsCommentaries(id)
   getDataOfCP({IoC: id})
  }).then(() => {
   validation(id)
  }).catch((error) => { console.log(error) })
 }).catch((error) => { console.log(error) })
}

function addCustomer(){
 axios({
  method: 'post',
  url: `/_php/f/c/u.php`,
  data: {h_IoC: ''}
 }).then((response) => {
  openForm(response.data)
 }).catch((error) => { console.log(error) })
}

function getDataOfC(data){
 axios({
  method: 'post',
  url: '/_php/f/c/s.php',
  data
 }).then((response) => {
  document.querySelector('#h_IoC').value = response.data.Index
  document.querySelector('#t_name').value = response.data.Name
  document.querySelector('#t_city').value = response.data.City
  document.querySelector('#t_inn').value = (+response.data.INN === NaN) ? 0 : +response.data.INN
  document.querySelector('#ta_sites').value = response.data.Sites
  document.querySelector('#t_specialization').value = response.data.Specialization
  document.querySelector('#t_manufacturers').value = response.data.Products
  document.querySelector('#s_status').value = response.data.IoS
  document.querySelector('#s_type').value = response.data.IoT
  document.querySelector('#s_manager').value = response.data.IoU
  //< Loyalty
  {
   const sLoyalty = document.querySelector('#s_loyalty')
   sLoyalty.value = response.data.IoL
   const currentLoyalty = sLoyalty.querySelector(`Option[value="${response.data.IoL}"]`)
   if(currentLoyalty !== null){
    const colorOfLoyalty = currentLoyalty.textContent

    if(colorOfLoyalty === 'red'){ sLoyalty.style.backgroundColor = 'rgba(255, 0, 0, 0.12)' }
    else if(colorOfLoyalty === 'green'){ sLoyalty.style.backgroundColor = 'rgba(0, 255, 0, 0.12)' }
    else if(colorOfLoyalty === 'yellow'){ sLoyalty.style.backgroundColor = 'rgba(255, 255, 0, 0.12)' }
    else if(colorOfLoyalty === 'silver'){ sLoyalty.style.backgroundColor = 'rgba(0, 0, 0, 0.12)' }
    else{ console.log('colorOfLoyalty = ', colorOfLoyalty) }
   }
  }
  //> Loyalty

  if(response.data['Group of current user'] !== 1){
   if(response.data.Name !== ''){ document.querySelector('#t_name').setAttribute('disabled', 'true') }
   if(response.data.City !== ''){ document.querySelector('#t_city').setAttribute('disabled', 'true') }
   if(response.data.INN.length === 10){ document.querySelector('#t_inn').setAttribute('disabled', 'true') }
   if(response.data.Sites !== ''){ document.querySelector('#ta_sites').setAttribute('disabled', 'true') }
   if(response.data.Products !== ''){ document.querySelector('#ta_products').setAttribute('disabled', 'true') }
  }
 }).catch((error) => { console.log(error) })
}

function gsCommentaries(IoC = document.querySelector('#h_IoC').value){
 const oData = {}
 oData.IoC = IoC
 if(document.querySelector('#ta_commentaries').value.length > 0){
  oData.Comment = document.querySelector('#ta_commentaries').value
 }

 let commentaries = ''
 let date = null

 axios({
  method: 'post',
  url: `/_php/f/c_s/iu.php`,
  data: oData
 }).then((response) => {
  for(v of response.data){
   const a_Date = v[0].match(/..?/g)
   date = `${a_Date[3]}:${a_Date[4]}:${a_Date[5]} ${a_Date[2]}-${a_Date[1]}-20${a_Date[0]}`

   commentaries += `<div><strong>${v[2]}<i>(${date})</i></strong><p>${v[1]}</p></div>`
  }
  document.querySelector('Div#commentaries > Div').innerHTML = commentaries
  document.querySelector('#ta_commentaries').value = ''
 }).catch((error) => { console.log(error) })
}
document.addEventListener('keydown', (e) => { if(e.code === 'Enter' && e.altKey){ gsCommentaries() } })

function getDataOfCP(data){
 axios({
  method: 'post',
  url: '/_php/f/cp/s.php',
  data
 }).then((response) => {
  document.querySelector('#h_IoCP').value = (response.data.Index === undefined) ? '' : response.data.Index
  //< Name
  document.querySelector('#t_cp').value = ''
  if(typeof response.data.Name === 'undefined'){
      (typeof response.data.Last !== 'undefined') && (document.querySelector('#t_cp').value = response.data.Last)
      if(typeof response.data.First !== 'undefined'){
          (document.querySelector('#t_cp').value !== '') && (document.querySelector('#t_cp').value += ' ')
          document.querySelector('#t_cp').value += response.data.First
      }
      if(typeof response.data.Patronymic !== 'undefined'){
          (document.querySelector('#t_cp').value !== '') && (document.querySelector('#t_cp').value += ' ')
          document.querySelector('#t_cp').value += response.data.Patronymic
      }
    } else{
      document.querySelector('#t_cp').value = response.data.Name
  }
  //> Name
  document.querySelector('#t_position').value = (response.data.Position === undefined) ? '' : response.data.Position
  document.querySelector('#s_authority').value = (response.data.IoA === undefined) ? '' : response.data.IoA
  document.querySelector('#ta_phones').value = (response.data.Phones === undefined) ? '' : response.data.Phones
  document.querySelector('#ta_eMails').value = (response.data.eMails === undefined) ? '' : response.data.eMails
  document.querySelector('#d_cpDate').value = (response.data.cpDate === '0000-00-00') ? '' : response.data.cpDate
  document.querySelector('#s_manager').value = (response.data.IoU === undefined) ? '' : response.data.IoU

  //console.log('response.data', response.data)
  if(response.data['Group of current user'] !== 1){
   if(response.data.Name !== ''){ document.querySelector('#t_name').setAttribute('disabled', 'true') }
   if(response.data.Position !== ''){ document.querySelector('#t_position').setAttribute('disabled', 'true') }
   if(response.data.IoA !== ''){ document.querySelector('#s_authority').setAttribute('disabled', 'true') }
   if(response.data.Phones !== ''){ document.querySelector('#ta_phones').setAttribute('disabled', 'true') }
   if(response.data.eMails !== ''){ document.querySelector('#ta_eMails').setAttribute('disabled', 'true') }
   if(response.data.cpDate !== '0000-00-00'){ document.querySelector('#d_cpDate').setAttribute('disabled', 'true') }
   if(response.data.IoU !== ''){ document.querySelector('#s_manager').setAttribute('disabled', 'true') }
  }

  return {'IoCP': response.data.Index, 'IoCPs': response.data.IoCPs}
 }).then(({IoCP, IoCPs}) => {
  const a_IoCPs = IoCPs.split(' ');
  const keyOfCurrentContactPerson = a_IoCPs.indexOf(IoCP)
  let IoNCP = a_IoCPs[keyOfCurrentContactPerson + 1]
  let IoPCP = a_IoCPs[keyOfCurrentContactPerson - 1]
  if(keyOfCurrentContactPerson == 0 && a_IoCPs.length > 1){
   IoPCP = a_IoCPs[a_IoCPs.length - 1]
   document.querySelector('#b_prev').setAttribute('disabled', true)
   if(IoNCP !== undefined){ document.querySelector('#b_next').removeAttribute('disabled') }
  } else if(keyOfCurrentContactPerson == a_IoCPs.length - 1 && a_IoCPs.length > 1){
   IoNCP = a_IoCPs[0]
   document.querySelector('#b_next').setAttribute('disabled', true)
   if(IoPCP !== undefined){ document.querySelector('#b_prev').removeAttribute('disabled') }
  } else if(a_IoCPs.length === 1){
   document.querySelector('#b_next').setAttribute('disabled', 'disabled')
   document.querySelector('#b_prev').setAttribute('disabled', 'disabled')
  }

  return {'IoNCP': IoNCP, 'IoPCP': IoPCP}
 }).then(({IoNCP, IoPCP}) => {
  document.querySelector('#b_next').setAttribute('onClick', `getDataOfCP({'IoCP': '${IoNCP}'})`)
  document.querySelector('#b_prev').setAttribute('onClick', `getDataOfCP({'IoCP': '${IoPCP}'})`)
  document.querySelector('#b_new').addEventListener('click', (e) => {
   e.target.closest('Div#forms Form:last-of-type').reset()
   document.querySelector('#h_IoCP').value = ''
  })
 }).catch((error) => { console.log(error) })
}

const updateForms = (A, B) => {
 let aElementsOfForm = document.querySelector(`div#forms > form:${A}st-of-type`).elements
 //console.log('aElementsOfForm', aElementsOfForm)

 data = {}
 for(k in aElementsOfForm){
  if(aElementsOfForm[k].name === 's_manufacturers'){
   data[aElementsOfForm[k].name] = Array.from(aElementsOfForm[k].options).filter(option => option.selected).map(option => option.value);
  } else if(aElementsOfForm[k].name){
   data[aElementsOfForm[k].name] = aElementsOfForm[k].value
  }
 }
 (B !== '') && (data.h_IoC = document.querySelector(`#h_IoC`).value);
 console.log(`updateForms > ${A} :`, data)

 axios({
  method: 'post',
  url: `/_php/f/${B}/u.php`,
  data
 }).then((response) => {
  document.querySelector('#h_Io' + B.toUpperCase()).value = response.data
 }).catch((error) => { console.log(error) })
}
function updateC(){
 updateForms('fir', 'c')
 aside.classList.remove('active')
 getTable()
}
function updateCP(){
    updateForms('la', 'cp')
    getTable()
}

document.addEventListener('mousedown', (e) => {
 if(e.target.closest('Div#aside') === null){
  aside.classList.remove('active')
 }
})

//< Validation
function validation(IoC){
 //< ИНН
 let t_inn = (e) => {
  if(!(+e.target.value === NaN)){ e.target.value = e.target.value.replace(/\D/g, '') }
  if(e.target.value.length === 10){
   axios({
    method: 'post',
    url: `/_php/f/validation/c.inn.php`,
    data: {
     INN: e.target.value,
     IoC
    }
   }).then((response) => {
    if(response.data){ e.target.classList.remove('incorrect') }
    else{ e.target.classList.add('incorrect') }
   }).then(toggleButton).catch((error) => { console.log(error) })
  } else{
   e.target.classList.add('incorrect')
  }

  toggleButton()
 }
 document.querySelector('#t_inn').oninput = t_inn
 document.querySelector('#t_inn').onpaste = t_inn
 //> ИНН

 //< Sites
 let ta_sites = (e) => {
  if(!(e.target.value === '')){
   e.target.value = e.target.value.replace(/^http(s?):\/\//gi, '')
   e.target.value = e.target.value.replace(/\/\//g, '')
   e.target.value = e.target.value.replace(/www./gi, '')
   e.target.value = e.target.value.replace(/\s/g, '\r\n')
  }
 }
 document.querySelector('#ta_sites').onfocus = ta_sites
 document.querySelector('#ta_sites').oninput = ta_sites
 document.querySelector('#ta_sites').onpaste = ta_sites
 //> Sites

 //< eMails
 let ta_eMails = (e) => {
  if(!(e.target.value === '')){
   e.target.value = e.target.value.replace(/\s/g, '\r\n')
  }
 }
 document.querySelector('#ta_eMails').onfocus = ta_eMails
 document.querySelector('#ta_eMails').oninput = ta_eMails
 document.querySelector('#ta_eMails').onpaste = ta_eMails
 //> eMails

 function toggleButton(){
  const button = document.querySelector('Form > Div > Button')
  if(document.querySelector('.incorrect')){
   button.setAttribute('disabled', 'true')
  } else{
   button.removeAttribute('disabled')
  }
 }
}
//> Validation
