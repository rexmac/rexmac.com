# Extensions to the Jekyll Page class
module Jekyll
  class Page
    def body_id
      self.data['title'] ? "page-" + self.data['title'].slugize : nil
    end

    alias orig_to_liquid to_liquid
    def to_liquid
      h = orig_to_liquid
      h['body_id'] = self.body_id
      h
    end
  end
end
