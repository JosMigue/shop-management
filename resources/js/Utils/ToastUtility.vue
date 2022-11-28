<script>
  import { useToast } from "vue-toastification";
  export default {
    setup() {
      const toast = useToast();
      return { toast }
    },
    data() {
      return {
        toastconfig: {
          position: "bottom-right",
          timeout: 3010,
          closeOnClick: true,
          pauseOnFocusLoss: true,
          pauseOnHover: true,
          draggable: true,
          draggablePercent: 0.6,
          showCloseButtonOnHover: false,
          hideProgressBar: true,
          closeButton: "button",
          icon: true,
          rtl: false
        }
      }
    },
    methods: {
      successToastNotification : function(message) {
        this.toast.success(message, this.toastconfig);
      },
      errorToastNotification: function(message){
        this.toast.error(message, this.toastconfig);
      },
      infoToastNotification: function(message){
        this.toast.info(message, this.toastconfig);
      },
      warningToastNotification: function(message){
        this.toast.warning(message, this.toastconfig);
      },
    },
  }
</script>