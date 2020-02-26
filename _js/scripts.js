const aside = document.querySelector('Body > Aside')
const asideDivDiv = document.querySelector('Div#aside')

function getTable(){
 axios({
  method: 'post',
  url: '/_php/pages/t/i.php',
  data: {}
 }).then((response) => {
  document.querySelector('Div#main').innerHTML = response.data
 }).then(() => {
  const aTr = document.querySelectorAll('TBody > Tr');
  let IoC
  aTr.forEach((v) => {
   v.childNodes.forEach((vv, kk) => {
    if(kk === 0){ IoC = vv.innerHTML }
    if(kk === 1){ v.setAttribute('onClick', `openForm('` + IoC + `')`) }
   })
  })
 }).then(() => {
  document.querySelectorAll('TBody > Tr').forEach((v) => {
   let aTd = v.children
   if(aTd[1].textContent === 'r'){ aTd[1].style.backgroundColor = 'rgba(255, 0, 0, 0.12)'; v.style.backgroundColor = 'rgba(255, 0, 0, 0.08)' }
   else if(aTd[1].textContent === 'g'){ aTd[1].style.backgroundColor = 'rgba(0, 255, 0, 0.12)'; v.style.backgroundColor = 'rgba(0, 255, 0, 0.08)' }
   else if(aTd[1].textContent === 'y'){ aTd[1].style.backgroundColor = 'rgba(255, 255, 0, 0.12)'; v.style.backgroundColor = 'rgba(255, 255, 0, 0.08)' }
  })
 }).catch((error) => { console.log(error) })
}

function openForm(id){
 aside.classList.add('active')

 axios({
  method: 'post',
  url: '/_php/forms/i.php',
  data: {id: id}
 }).then(function(response){
  asideDivDiv.innerHTML = response.data
  getDataOfC( {IoC: id})
  gsCommentaries(id)
  getDataOfCP({IoC: id})
 }).catch((error) => { console.log(error) })
}

function addCustomer(){
 axios({
  method: 'post',
  url: `/_php/forms/c/u.php`,
  data: {IoC: ''}
 }).then((response) => {
  openForm(response.data)
 }).catch((error) => { console.log(error) })
}

function getDataOfC(data){
 axios({
  method: 'post',
  url: '/_php/forms/c/s.php',
  data
 }).then((response) => {
  document.querySelector('#h_IoC').value = response.data.Index
  document.querySelector('#t_name').value = response.data.Name
  document.querySelector('#t_city').value = response.data.City
  document.querySelector('#n_inn').value = (+response.data.INN === NaN) ? 0 : +response.data.INN
  document.querySelector('#ta_sites').value = response.data.Sites
  document.querySelector('#t_specialization').value = response.data.Specialization
  document.querySelector('#ta_products').value = response.data.Products
  document.querySelector('#s_status').value = response.data.IoS
  document.querySelector('#s_type').value = response.data.IoT
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
    else if(colorOfLoyalty === 'grey'){ sLoyalty.style.backgroundColor = 'rgba(0, 0, 0, 0.12)' }
    else{ console.log('colorOfLoyalty = ', colorOfLoyalty) }
   }
  }
  //> Loyalty


  if(response.data.Name !== ''){ document.querySelector('#t_name').setAttribute('disabled', 'true') }
  if(response.data.City !== ''){ document.querySelector('#t_city').setAttribute('disabled', 'true') }
  if(response.data.INN.lenght < 10){ document.querySelector('#n_inn').setAttribute('disabled', 'true') }
  if(response.data.Sites !== ''){ document.querySelector('#ta_sites').setAttribute('disabled', 'true') }
  if(response.data.Products !== ''){ document.querySelector('#ta_products').setAttribute('disabled', 'true') }
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
  url: `/_php/forms/c_s/iu.php`,
  data: oData
 }).then((response) => {
  for(v of response.data){
   const a_Date = v[0].match(/..?/g)
   date = `${a_Date[3]}:${a_Date[4]}:${a_Date[5]} ${a_Date[2]}-${a_Date[1]}-20${a_Date[0]}`

   commentaries += `<div><strong>${v[2]}<i>(${date})</i></strong><p>${v[1]}</p></div>`
  }
  document.querySelector('Div#commentaries > Div').innerHTML = commentaries
 }).catch((error) => { console.log(error) })
}
document.addEventListener('keydown', (e) => { if(e.code === 'Enter' && e.altKey){ gsCommentaries() } })

function getDataOfCP(data){
 axios({
  method: 'post',
  url: '/_php/forms/cp/s.php',
  data
 }).then((response) => {
  document.querySelector('#h_IoCP').value = (response.data.Index === undefined) ? '' : response.data.Index
  document.querySelector('#t_last').value = (response.data.Last === undefined) ? '' : response.data.Last
  document.querySelector('#t_first').value = (response.data.First === undefined) ? '' : response.data.First
  document.querySelector('#t_patronymic').value = (response.data.Patronymic === undefined) ? '' : response.data.Patronymic
  document.querySelector('#t_position').value = (response.data.Position === undefined) ? '' : response.data.Position
  document.querySelector('#s_authority').value = (response.data.IoA === undefined) ? '' : response.data.IoA
  document.querySelector('#ta_phones').value = (response.data.Phones === undefined) ? '' : response.data.Phones
  document.querySelector('#ta_eMails').value = (response.data.eMails === undefined) ? '' : response.data.eMails

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

function updateForms(){
 const f = (A, B) => {
  let aElementsOfForm = document.querySelector(`Div#forms > Form:${A}st-of-type`).elements
  data = {}
  for(k in aElementsOfForm){ data[aElementsOfForm[k].name] = aElementsOfForm[k].value }
  if(B !== ''){ data.h_IoC = document.querySelector(`#h_IoC`).value }
  axios({
   method: 'post',
   url: `/_php/forms/${B}/u.php`,
   data
  }).then(function(response){
   document.querySelector('#h_Io' + B.toUpperCase()).value = response.data
  }).catch((error) => { console.log(error) })
 }

 f('fir', 'c')
 f('la', 'cp')

 getTable()
}

document.addEventListener('mousedown', (e) => {
 if(e.target.closest('Div#aside') === null){
  aside.classList.remove('active')
 }
})