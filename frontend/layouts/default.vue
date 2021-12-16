<template>
    <div>
        <Navbar/>
        <div class="content-wrapper">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header mb-5">                    
                </div>
                <div class="content">
                    <nuxt/>
                </div>
            </div>
        </div>    
    </div>
</template>

<script>
  import Navbar from '@/components/Navbar'
  export default {
    components: {
      Navbar
    }
  }
</script>
