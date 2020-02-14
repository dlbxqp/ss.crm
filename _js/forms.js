const aside = document.querySelector('Body > Aside')
const asideDivDiv = document.querySelector('Div#aside')

function openForm(id){
 aside.classList.add('active')

 axios({
  method: 'post',
  url: '/_php/forms/i.php',
  data: {id: id}
 }).then(function(response){
  asideDivDiv.innerHTML = response.data
  getDataOfC( {IoC: id})
  getDataOfCP({IoC: id})
 }).catch(function(error){ console.log(error) })
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
  document.querySelector('#ta_comments').value = response.data.Comments
  document.querySelector('#s_loyalty').value = response.data.IoL
  document.querySelector('#s_status').value = response.data.IoS
  document.querySelector('#s_type').value = response.data.IoT
 }).catch(function(error){ console.log(error) })
}

function getDataOfCP(data){
 axios({
  method: 'post',
  url: '/_php/forms/cp/s.php',
  data
 }).then((response) => {
  document.querySelector('#h_IoCP').value = response.data.Index
  document.querySelector('#t_last').value = response.data.Last
  document.querySelector('#t_first').value = response.data.First
  document.querySelector('#t_patronymic').value = response.data.Patronymic
  document.querySelector('#t_position').value = response.data.Position
  document.querySelector('#s_authority').value = response.data.IoA
  document.querySelector('#ta_phones').value = response.data.Phones
  document.querySelector('#ta_eMails').value = response.data.eMails

  return {'IoCP': response.data.Index, 'IoCPs': response.data.IoCPs}
 }).then(({IoCP, IoCPs}) => {
  const a_IoCPs = IoCPs.split(' ')
  const keyOfCurrentContactPerson = a_IoCPs.indexOf(IoCP)
  let IoNCP = a_IoCPs[keyOfCurrentContactPerson + 1]
  let IoPCP = a_IoCPs[keyOfCurrentContactPerson - 1]
  if(keyOfCurrentContactPerson == 0){
   IoPCP = a_IoCPs[a_IoCPs.length - 1]
   document.querySelector('#b_prev').setAttribute('disabled', true)
   if(IoNCP !== undefined){ document.querySelector('#b_next').removeAttribute('disabled') }
  }
  else if(keyOfCurrentContactPerson == a_IoCPs.length - 1){
   IoNCP = a_IoCPs[0]
   document.querySelector('#b_next').setAttribute('disabled', true)
   if(IoPCP !== undefined){ document.querySelector('#b_prev').removeAttribute('disabled') }
  }

  return {'IoNCP': IoNCP, 'IoPCP': IoPCP}
 }).then(({IoNCP, IoPCP}) => {
  document.querySelector('#b_next').setAttribute('onClick', `getDataOfCP({'IoCP': '${IoNCP}'})`)
  document.querySelector('#b_prev').setAttribute('onClick', `getDataOfCP({'IoCP': '${IoPCP}'})`)
 }).catch(function(error){ console.log(error) })
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
   document.querySelector('#h_IoC' + B.toUpperCase()).value = response.data
  }).catch(function(error){ console.log(error) })
 }

 f('fir', 'c')
 f('la', 'cp')

 document.location.href = '/'
}

document.addEventListener('mousedown', function(e){
 if(e.target.closest('Div#aside') === null){
  aside.classList.remove('active')
 }
})