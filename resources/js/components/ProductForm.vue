<template>
  <div class="container my-4">
    <div v-if="isLoading" class="loader-container">
      <div class="loader"></div>
    </div>
    <form v-else @submit.prevent="handleSubmit">
      <div class="row mb-3">
        <div class="col-md-12">
          <div class="card upload-card">
            <div class="card-body">
              <div @dragover.prevent @dragenter.prevent @drop="handleDrop" @click="triggerFileInput" class="upload-container">
                <input type="file" @change="handleFileChange" ref="fileInput" class="file-input" accept="image/*" />
                <div v-if="imageSrc">
                  <img :src="imageSrc" class="img-preview" alt="Image Preview"/>
                </div>
                <div v-else class="placeholder">
                  <i class="fas fa-upload fa-3x"></i>
                  <p class="mt-2">Drag and drop an image here or click to select</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="name">اسم المنتج</label>
            <input type="text" v-model="form.name" class="form-control" id="name" placeholder="اسم المنتج" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="category">الفئة</label>
            <select v-model="form.category_id" @change="handleCategoryChange" class="form-control">
              <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
            </select>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group">
            <label for="description">الوصف</label>
            <textarea v-model="form.description" class="form-control" id="description" rows="10" placeholder="وصف المنتج"></textarea>
          </div>
        </div>

        <div v-if="showRequirements">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>اسم المتطلب</th>
                <th>نوع المتطلب</th>
                <th>عناصر القائمة</th>
                <th>إجراءات</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(requirement, index) in requirements" :key="index">
                <td>
                  <input type="text" v-model="requirement.name" class="form-control" placeholder="اسم المتطلب">
                </td>
                <td>
                  <select v-model="requirement.type" class="form-control">
                    <option value="text">كتابي</option>
                    <option value="list">قائمة</option>
                  </select>
                </td>
                <td>
                  <div v-if="requirement.type === 'list'">
                    <div v-for="(item, itemIndex) in requirement.listItems" :key="itemIndex" class="input-group mb-2">
                      <input type="text" v-model="requirement.listItems[itemIndex]" class="form-control" placeholder="عنصر القائمة">
                      <button type="button" class="btn btn-danger" @click="removeListItem(index, itemIndex)">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                    <button type="button" @click="addListItem(index)" class="btn btn-secondary btn-sm">إضافة عنصر</button>
                  </div>
                </td>
                <td>
                  <button type="button" @click="removeRequirement(index)" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i> حذف
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <button type="button" @click="addRequirement" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> إضافة متطلب
          </button>
        </div>

        <button type="submit" class="btn btn-primary mt-3">{{ isEdit ? 'تحديث' : 'إنشاء' }}</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: ['product', 'img'],
  data() {
    return {
      isLoading: false,
      isEdit: false,
      form: {
        name: '',
        category_id: null,
        description: ''
      },
      imageSrc: this.img || '',
      categories: [],
      requirements: [
        { name: '', type: 'text', listItems: [] }
      ],
      showRequirements: false
    };
  },
  methods: {
    handleDrop(e) {
      e.preventDefault();
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        this.processFile(files[0]);
      }
    },
    handleFileChange(e) {
      const files = e.target.files;
      if (files && files[0]) {
        this.processFile(files[0]);
      }
    },
    processFile(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.imageSrc = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    handleCategoryChange() {
      this.showRequirements = this.form.category_id === 1;
    },
    addRequirement() {
      this.requirements.push({ name: '', type: 'text', listItems: [] });
    },
    removeRequirement(index) {
      this.requirements.splice(index, 1);
    },
    addListItem(requirementIndex) {
      this.requirements[requirementIndex].listItems.push('');
    },
    removeListItem(requirementIndex, itemIndex) {
      this.requirements[requirementIndex].listItems.splice(itemIndex, 1);
    },
    init() {
      this.isLoading = true;
      const productId = this.product ? this.product : null;
      this.$instance.get('/categories', { params: { product_id: productId }}).then(r => {
        this.categories = r.data.categories;
        if (r.data.product) {
          this.isEdit = true;
          this.form = { ...r.data.product };
          this.requirements = r.data.product.requirements.map(req => {
            return {
              ...req,
              listItems: req.list_items.map(item => item.item)
            };
          });
          this.showRequirements = this.form.category_id === 1;
        }
      }).then(() => {
        this.isLoading = false;
      });
    },
    handleSubmit() {
      this.isLoading = true; // Show loader

      // Basic validation
      if (!this.form.name.trim() || !this.form.category_id || !this.form.description.trim()) {
        this.$toastr.error('جميع الحقول مطلوبه');
        this.isLoading = false; // Hide loader if validation fails
        return;
      }

      const formData = new FormData();
      formData.append('name', this.form.name);
      formData.append('category_id', this.form.category_id);
      formData.append('description', this.form.description);
      if (this.$refs.fileInput.files[0]) {
        formData.append('image', this.$refs.fileInput.files[0]);
      }

      // Append requirements if any
      this.requirements.forEach((req, index) => {
        formData.append(`requirements[${index}][name]`, req.name);
        formData.append(`requirements[${index}][type]`, req.type);
        if (req.type === 'list') {
          req.listItems.forEach((item, itemIndex) => {
            formData.append(`requirements[${index}][listItems][${itemIndex}]`, item);
          });
        }
      });

      const url = this.isEdit ? `/products/${this.product}` : '/products';
      const method = 'post';

      this.$instance[method](url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        this.isLoading = false;

        const message = this.isEdit ? 'تم تحديث المنتج بنجاح' : 'تمت اضافة المنتج بنجاح';
        this.$toastr.success(message);

        setTimeout(() => {
          window.location.href = '/products';
        }, 600);
      })
      .catch((error) => {
        this.isLoading = false;
        this.$toastr.error('حدث خطا ما يرجى التواصل مع الدعم الفني');
        console.error("Submission error:", error);
      });
    },
    triggerFileInput() {
      this.$refs.fileInput.click();
    }
  },
  mounted() {
    this.init();
  }
};
</script>

<style scoped>
.container {
  max-width: 100%;
  padding: 15px;
  background-color: #f8f9fa;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.card {
  width: 100%; 
  margin-bottom: 20px;
}
.card-body {
  padding: 15px;
}
.placeholder, .upload-container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  width: 100%;
  min-height: 150px;
  border: 2px dashed #ccc;
  border-radius: 5px;
  text-align: center;
  padding: 10px;
  background-color: #f0f0f0; /* Lighter background for better visibility */
  color: #333; /* Darker text for contrast */
}
.file-input {
  width: 0;
  height: 0;
  opacity: 0;
  position: absolute;
}
.img-preview {
  max-width: 100%;
  max-height: 100%;
}
.form-group {
  width: 100%;
  margin-bottom: 10px;
}
.form-control {
  width: 100%;
  padding: 8px;
  font-size: 16px;
}
.btn, .btn-sm {
  width: 100%;
}
@media (min-width: 768px) {
  .col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
  }
  .col-md-12 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  .btn, .btn-sm {
    width:auto;
  }
}
.table {
  width: 100%;
  overflow-x: auto;
}
.loader-container {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.8);
  z-index: 1000;
}
.loader {
  border: 16px solid #f3f3f3;
  border-top: 16px solid #3498db;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
